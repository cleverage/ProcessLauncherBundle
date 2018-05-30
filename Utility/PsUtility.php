<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle\Utility;

/**
 * Parse ps command output.
 *
 * Because the output of ps uses spaces to separate values, it cannot be parsed directly without using some dirty tricks
 */
class PsUtility
{
    /**
     * Only used in combination with AIX headers
     *
     * @var string
     */
    const DELIMITER = ';';

    /**
     * @var array safe AIX headers, the only one with custom delimiter support (%a is not safe)
     */
    protected $aixHeaders = [
        '%p' => 'PID',
        '%P' => 'PPID',
        '%r' => 'PGID',
        '%U' => 'USER',
        '%G' => 'GROUP',
        '%u' => 'RUSER',
        '%g' => 'RGROUP',
        '%y' => 'TTY',
        '%n' => 'NI',
        '%C' => '%CPU',
        '%t' => 'ELAPSED',
        '%x' => 'TIME',
        '%z' => 'VSZ',
    ];

    /**
     * @var array Standard linux format headers, without custom delimiter support but with fixed size support
     */
    protected $otherHeaders = [
        'pid:8' => 'PID',
        'stat:5' => 'STAT',
        'class:5' => 'CLS',
        'rss:10' => 'RSS',
        '%mem:5' => '%MEM',
        'command' => 'COMMAND', // Only last value does not need a size
    ];

    /**
     * Get some process info based on it's PID
     *
     * @param int $pid
     *
     * @return array|false
     */
    public function getProcessInfo(int $pid)
    {
        $processes = $this->getProcessesInfo([$pid]);

        return reset($processes);
    }

    /**
     * List processes, filter by PIDs
     *
     * @param array $pids
     *
     * @return array
     */
    public function getProcessesInfo(array $pids = []): array
    {
        // AIX serves as a base for all other info because it's capability to use a custom delimiter is more reliable
        $processes = $this->getAixProcessesInfo($pids);

        foreach ($this->getOtherProcessesInfo($pids) as $pid => $info) {
            foreach ($info as $key => $value) {
                $processes[$pid][$key] = $value;
            }
        }

        // Filter incomplete processes (the one holding the ps command)
        foreach ($processes as $key => $info) {
            if (\count($info) !== \count($this->aixHeaders) + \count($this->otherHeaders) - 1) { // -1 is for PID
                unset($processes[$key]);
            }
        }

        return $processes;
    }

    /**
     * @param array $pids
     *
     * @return array
     */
    protected function getAixProcessesInfo(array $pids): array
    {
        $output = $this->execCommand(implode(self::DELIMITER, array_keys($this->aixHeaders)), $pids);

        $lines = explode("\n", $output);
        $processes = [];
        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }

            $processInfo = array_combine(
                $this->aixHeaders,
                array_map('trim', str_getcsv($line, self::DELIMITER))
            );

            $processes[$processInfo['PID']] = $processInfo;
        }

        return $processes;
    }

    /**
     * @param array $pids
     *
     * @return array
     */
    protected function getOtherProcessesInfo(array $pids): array
    {
        $output = $this->execCommand(implode(',', array_keys($this->otherHeaders)), $pids);

        $lines = explode("\n", $output);
        $processes = [];
        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }

            $processInfo = [];
            $start = 0;
            foreach ($this->otherHeaders as $arg => $header) {
                $argInfo = explode(':', $arg);
                $size = $argInfo[1] ?? null;
                if (null !== $size) {
                    $processInfo[$header] = trim(substr($line, $start, $size));
                    $start += $size + 1;
                } else { // Last value doesn't need to set a size
                    $processInfo[$header] = trim(substr($line, $start));
                }
            }

            $processes[$processInfo['PID']] = $processInfo;
        }

        return $processes;
    }

    /**
     * @param string $customHeaders
     * @param array  $pids
     *
     * @return string
     */
    protected function execCommand(string $customHeaders, array $pids)
    {
        $allOption = 0 === \count($pids) ? '-e' : '';
        $cmd = "ps {$allOption} -ww --no-headers -o ".escapeshellarg($customHeaders);
        if (0 !== \count($pids)) {
            $cmd .= ' -p '.escapeshellarg(implode(',', $pids));
        }

        return shell_exec($cmd);
    }
}
