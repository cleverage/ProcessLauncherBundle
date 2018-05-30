<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle\DependencyInjection;

use CleverAge\ProcessLauncherBundle\Doctrine\Type\ProcessConfigurationType;
use Sidus\BaseBundle\DependencyInjection\SidusBaseExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class CleverAgeProcessLauncherExtension extends SidusBaseExtension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        parent::load($configs, $container);

        // Injecting custom doctrine type
        $doctrineTypes = $container->getParameter('doctrine.dbal.connection_factory.types');
        $doctrineTypes[ProcessConfigurationType::TYPE_NAME] = [
            'class' => ProcessConfigurationType::class,
            'commented' => true,
        ];
        $container->setParameter('doctrine.dbal.connection_factory.types', $doctrineTypes);
    }
}
