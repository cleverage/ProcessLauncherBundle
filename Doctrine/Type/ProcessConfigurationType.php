<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle\Doctrine\Type;

use CleverAge\ProcessLauncherBundle\Exception\MissingProcessConfigurationException;
use CleverAge\ProcessLauncherBundle\Process\ProcessConfigurationInterface;
use CleverAge\ProcessLauncherBundle\Registry\ProcessConfigurationRegistry;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Allows to "store" process configurations in a database relations
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class ProcessConfigurationType extends StringType
{
    const TYPE_NAME = 'process_configuration';

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @throws MissingProcessConfigurationException
     *
     * @return null|ProcessConfigurationInterface
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }
        $listeners = $platform->getEventManager()->getListeners('clever_process_configuration_registry');

        /** @var ProcessConfigurationRegistry $processConfigurationRegistry */
        $processConfigurationRegistry = reset($listeners);

        return $processConfigurationRegistry->getProcessConfiguration($value);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value instanceof ProcessConfigurationInterface) {
            throw new \UnexpectedValueException('Value must implements ProcessConfigurationInterface');
        }

        return $value->getCode();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return static::TYPE_NAME;
    }
}
