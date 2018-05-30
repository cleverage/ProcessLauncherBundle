<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle;

use CleverAge\ProcessLauncherBundle\Registry\ProcessConfigurationRegistry;
use Sidus\BaseBundle\DependencyInjection\Compiler\GenericCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Register compiler passes
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class CleverAgeProcessLauncherBundle extends Bundle
{
    /**
     * Adding compiler passes to inject services into configuration handlers
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new GenericCompilerPass(
                ProcessConfigurationRegistry::class,
                'cleverage.process_configuration',
                'addProcessConfiguration'
            )
        );
    }
}
