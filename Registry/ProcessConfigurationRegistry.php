<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle\Registry;

use CleverAge\ProcessLauncherBundle\Exception\MissingProcessConfigurationException;
use CleverAge\ProcessLauncherBundle\Process\ProcessConfigurationInterface;

/**
 * Stores process configurations
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class ProcessConfigurationRegistry
{
    /** @var ProcessConfigurationInterface[] */
    protected $processConfigurations;

    /**
     * @param ProcessConfigurationInterface $processConfiguration
     */
    public function addProcessConfiguration(ProcessConfigurationInterface $processConfiguration)
    {
        $this->processConfigurations[$processConfiguration->getCode()] = $processConfiguration;
    }

    /**
     * @return ProcessConfigurationInterface[]
     */
    public function getProcessConfigurations(): array
    {
        return $this->processConfigurations;
    }

    /**
     * @return array
     */
    public function getProcessConfigurationCodes(): array
    {
        return array_keys($this->processConfigurations);
    }

    /**
     * @param string $code
     *
     * @throws MissingProcessConfigurationException
     *
     * @return ProcessConfigurationInterface
     */
    public function getProcessConfiguration($code): ProcessConfigurationInterface
    {
        if (!$this->hasProcessConfiguration($code)) {
            throw MissingProcessConfigurationException::create($code);
        }

        return $this->processConfigurations[$code];
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function hasProcessConfiguration($code): bool
    {
        return array_key_exists($code, $this->processConfigurations);
    }
}
