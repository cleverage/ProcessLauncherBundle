<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle\Configuration;

use CleverAge\ProcessLauncherBundle\Process\ProcessConfigurationInterface;
use Symfony\Component\OptionsResolver\Exception\ExceptionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Simple process configuration example
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class ProcessConfiguration implements ProcessConfigurationInterface
{
    /** @var string */
    protected $code;

    /** @var array */
    protected $options = [];

    /** @var array */
    protected $requiredOptions = [];

    /** @var array */
    protected $defaultOptions = [];

    /** @var string */
    protected $command;

    /** @var string|null */
    protected $input;

    /** @var string|null */
    protected $workingDirectory;

    /**
     * @param string      $code
     * @param string      $command
     * @param null|string $input
     */
    public function __construct(string $code, string $command, string $input = null)
    {
        $this->code = $code;
        $this->command = $command;
        $this->input = $input;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @throws ExceptionInterface
     *
     * @return array
     */
    public function getOptions(): array
    {
        $optionResolver = new OptionsResolver();
        $optionResolver->setDefaults($this->defaultOptions);
        $optionResolver->setRequired($this->requiredOptions);

        return $optionResolver->resolve($this->options);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getRequiredOptions(): array
    {
        return $this->requiredOptions;
    }

    /**
     * @param array $requiredOptions
     */
    public function setRequiredOptions(array $requiredOptions)
    {
        $this->requiredOptions = $requiredOptions;
    }

    /**
     * @return array
     */
    public function getDefaultOptions(): array
    {
        return $this->defaultOptions;
    }

    /**
     * @param array $defaultOptions
     */
    public function setDefaultOptions(array $defaultOptions)
    {
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * @throws ExceptionInterface
     *
     * @return string
     */
    public function getCommand(): string
    {
        $options = [];
        foreach ($this->getOptions() as $key => $value) {
            $options['{{'.$key.'}}'] = escapeshellarg($value);
        }

        return strtr($this->command, $options);
    }

    /**
     * @return string|null
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return string|null
     */
    public function getWorkingDirectory()
    {
        return $this->workingDirectory;
    }

    /**
     * @param null|string $workingDirectory
     */
    public function setWorkingDirectory(string $workingDirectory)
    {
        $this->workingDirectory = $workingDirectory;
    }
}
