<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle\Serializer\Denormalizer;

use CleverAge\ProcessLauncherBundle\Manager\ProcessManager;
use CleverAge\ProcessLauncherBundle\Process\ProcessExecutionInterface;
use CleverAge\ProcessLauncherBundle\Registry\ProcessConfigurationRegistry;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Denormalize ProcessExecution using process manager
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class ProcessExecutionDenormalizer implements DenormalizerInterface
{
    /** @var ProcessConfigurationRegistry */
    protected $processConfigurationRegistry;

    /** @var ProcessManager */
    protected $processManager;

    /**
     * @param ProcessConfigurationRegistry $processConfigurationRegistry
     * @param ProcessManager               $processManager
     */
    public function __construct(
        ProcessConfigurationRegistry $processConfigurationRegistry,
        ProcessManager $processManager
    ) {
        $this->processConfigurationRegistry = $processConfigurationRegistry;
        $this->processManager = $processManager;
    }

    /**
     * Denormalizes data back into an object of the given class.
     *
     * @param mixed  $data    Data to restore
     * @param string $class   The expected class to instantiate
     * @param string $format  Format the given data was extracted from
     * @param array  $context Options available to the denormalizer
     *
     * @return ProcessExecutionInterface
     *
     * @throws BadMethodCallException   Occurs when the normalizer is not called in an expected context
     * @throws InvalidArgumentException Occurs when the arguments are not coherent or not supported
     * @throws UnexpectedValueException Occurs when the item cannot be hydrated with the given data
     * @throws ExtraAttributesException Occurs when the item doesn't have attribute to receive given data
     * @throws LogicException           Occurs when the normalizer is not supposed to denormalize
     * @throws RuntimeException         Occurs if the class cannot be instantiated
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (!\is_array($data)) {
            throw new UnexpectedValueException('$data must be an array');
        }
        if (!array_key_exists('processConfiguration', $data)) {
            throw new UnexpectedValueException('$data must contain the processConfiguration');
        }
        $processConfiguration = $this->processConfigurationRegistry->getProcessConfiguration(
            $data['processConfiguration']
        );
        if (array_key_exists('processOptions', $data)) {
            $processConfiguration->setOptions($data['processOptions']);
        }

        return $this->processManager->launchProcess($processConfiguration);
    }

    /**
     * Checks whether the given class is supported for denormalization by this normalizer.
     *
     * @param mixed  $data   Data to denormalize from
     * @param string $type   The class to which the data should be denormalized
     * @param string $format The format being deserialized from
     *
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return is_a($type, ProcessExecutionInterface::class, true);
    }
}
