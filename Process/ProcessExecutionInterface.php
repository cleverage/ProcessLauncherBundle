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
 * Represents an actual process execution
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
interface ProcessExecutionInterface
{
    /**
     * @return string
     */
    public function getUuid(): string;

    /**
     * @return ProcessConfigurationInterface|null
     */
    public function getProcessConfiguration();

    /**
     * Returns the Pid (process identifier), if applicable.
     *
     * @return int|null The process id if running, null otherwise
     */
    public function getPid();

    /**
     * Must return the date at which the process was launched
     *
     * @return \DateTime
     */
    public function getStartedAt(): \DateTime;

    /**
     * @return bool
     */
    public function isFinished(): bool;

    /**
     * @param bool $finished
     */
    public function setFinished(bool $finished);
}
