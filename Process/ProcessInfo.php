<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle\Process;

/**
 * Class-oriented result of ps linux command
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class ProcessInfo
{
    /** @var int */
    protected $pid;

    /** @var int */
    protected $ppid;

    /** @var int */
    protected $pgid;

    /** @var string */
    protected $user;

    /** @var string */
    protected $group;

    /** @var string */
    protected $ruser;

    /** @var string */
    protected $rgroup;

    /** @var string */
    protected $tty;

    /** @var int */
    protected $ni;

    /** @var float */
    protected $cpu;

    /** @var float */
    protected $mem;

    /** @var string */
    protected $elapsed;

    /** @var string */
    protected $time;

    /** @var int */
    protected $vsz;

    /** @var string */
    protected $stat;

    /** @var string */
    protected $cls;

    /** @var int */
    protected $rss;

    /** @var string */
    protected $command;

    /**
     * This class can only be instanciated through the ProcessManager
     */
    private function __construct()
    {
    }

    /**
     * Process ID number
     *
     * @return int
     */
    public function getPid(): int
    {
        return $this->pid;
    }

    /**
     * ID number of the process's parent process
     *
     * @return int
     */
    public function getPpid(): int
    {
        return $this->ppid;
    }

    /**
     * Process Group ID
     *
     * @return int
     */
    public function getPgid(): int
    {
        return $this->pgid;
    }

    /**
     * Username of the process's owner
     *
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * Group name of the process's owner
     *
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * Real owner username of the process
     *
     * @return string
     */
    public function getRuser(): string
    {
        return $this->ruser;
    }

    /**
     * Real owner group of the process
     *
     * @return string
     */
    public function getRgroup(): string
    {
        return $this->rgroup;
    }

    /**
     * Terminal associated with the process
     *
     * @return string
     */
    public function getTty(): string
    {
        return $this->tty;
    }

    /**
     * Nice value, this ranges from 19 (nicest) to -20 (not nice to others)
     *
     * @return int
     */
    public function getNi(): int
    {
        return $this->ni;
    }

    /**
     * Cpu utilization of the process
     *
     * @return float
     */
    public function getCpu(): float
    {
        return $this->cpu;
    }

    /**
     * Ratio of the process's resident set size to the physical memory on the machine, expressed as a percentage.
     *
     * @return float
     */
    public function getMem(): float
    {
        return $this->mem;
    }

    /**
     * Elapsed time since the process was started
     *
     * @return string
     */
    public function getElapsed(): string
    {
        return $this->elapsed;
    }

    /**
     * Total CPU usage
     *
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * Virtual memory size of the process in KiB
     *
     * @return int
     */
    public function getVsz(): int
    {
        return $this->vsz;
    }

    /**
     * Process status code
     *
     * Here are the different values that stat will display to describe the state of a process:
     * D   Uninterruptible sleep (usually IO)
     * R   Running or runnable (on run queue)
     * S   Interruptible sleep (waiting for an event to complete)
     * T   Stopped, either by a job control signal or because it is being traced.
     * W   paging (not valid since the 2.6.xx kernel)
     * X   dead (should never be seen)
     * Z   Defunct ("zombie") process, terminated but not reaped by its parent.
     *
     * Additional characters may be displayed:
     * <   high-priority (not nice to other users)
     * N   low-priority (nice to other users)
     * L   has pages locked into memory (for real-time and custom IO)
     * s   is a session leader
     * l   is multi-threaded (using CLONE_THREAD, like NPTL pthreads do)
     * +   is in the foreground process group
     *
     * @return string
     */
    public function getStat(): string
    {
        return $this->stat;
    }

    /**
     * Scheduling class of the process
     *
     * -     not reported
     * TS    SCHED_OTHER
     * FF    SCHED_FIFO
     * RR    SCHED_RR
     * B     SCHED_BATCH
     * ISO   SCHED_ISO
     * IDL   SCHED_IDLE
     * ?     unknown value
     *
     * @return string
     */
    public function getCls(): string
    {
        return $this->cls;
    }

    /**
     * Real memory usage, resident set size, the non-swapped physical memory that a task has used (in kiloBytes)
     *
     * @return int
     */
    public function getRss(): int
    {
        return $this->rss;
    }

    /**
     * Name of the process, including arguments, if any
     *
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }
}
