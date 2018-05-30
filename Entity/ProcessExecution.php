<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle\Entity;

use CleverAge\ProcessLauncherBundle\Process\ProcessConfigurationInterface;
use CleverAge\ProcessLauncherBundle\Process\ProcessExecutionInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class ProcessExecution implements ProcessExecutionInterface
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="string", length=32, unique=true)
     */
    protected $uuid;

    /**
     * @var ProcessConfigurationInterface
     *
     * @ORM\Column(type="process_configuration")
     */
    protected $processConfiguration;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $command;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $pid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $startedAt;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $finished = false;

    /**
     * @param ProcessConfigurationInterface $processConfiguration
     * @param int                           $pid
     */
    public function __construct(ProcessConfigurationInterface $processConfiguration, int $pid)
    {
        $this->uuid = bin2hex(random_bytes(16));
        $this->startedAt = new \DateTime();
        $this->processConfiguration = $processConfiguration;
        $this->command = $processConfiguration->getCommand();
        $this->pid = $pid;
    }

    /**
     * @return ProcessConfigurationInterface|null
     */
    public function getProcessConfiguration()
    {
        return $this->processConfiguration;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * Returns the Pid (process identifier), if applicable.
     *
     * @return int|null The process id if running, null otherwise
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * Must return the date at which the process was launched
     *
     * @return \DateTime
     */
    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return $this->finished;
    }

    /**
     * @param bool $finished
     */
    public function setFinished(bool $finished)
    {
        $this->finished = $finished;
    }
}
