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
 * Defines a process configuration that can be converted to a real process given some options
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
interface ProcessConfigurationInterface
{
    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @return string
     */
    public function getCommand(): string;

    /**
     * @return string|null
     */
    public function getInput();

    /**
     * @return string|null
     */
    public function getWorkingDirectory();
}
