<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\PaginatorInterface;
use ApiPlatform\Core\Exception\InvalidArgumentException;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use CleverAge\ProcessLauncherBundle\Exception\MissingProcessConfigurationException;
use CleverAge\ProcessLauncherBundle\Process\ProcessConfigurationInterface;
use CleverAge\ProcessLauncherBundle\Registry\ProcessConfigurationRegistry;

/**
 * Provides access to process configuration registry through Api Platform.
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class ProcessConfigurationDataProvider implements CollectionDataProviderInterface, ItemDataProviderInterface
{
    /** @var ProcessConfigurationRegistry */
    protected $processConfigurationRegistry;

    /**
     * @param ProcessConfigurationRegistry $processConfigurationRegistry
     */
    public function __construct(ProcessConfigurationRegistry $processConfigurationRegistry)
    {
        $this->processConfigurationRegistry = $processConfigurationRegistry;
    }

    /**
     * Retrieves a collection.
     *
     * @param string      $resourceClass
     * @param string|null $operationName
     *
     * @throws ResourceClassNotSupportedException
     *
     * @return ProcessConfigurationInterface[]|PaginatorInterface|\Traversable
     */
    public function getCollection(string $resourceClass, string $operationName = null)
    {
        if (!is_a($resourceClass, ProcessConfigurationInterface::class, true)) {
            throw new ResourceClassNotSupportedException('Resource class is not a ProcessConfigurationInterface');
        }

        return $this->processConfigurationRegistry->getProcessConfigurations();
    }

    /**
     * Retrieves an item.
     *
     * @param string      $resourceClass
     * @param int|string  $id
     * @param string|null $operationName
     * @param array       $context
     *
     * @throws InvalidArgumentException
     * @throws ResourceClassNotSupportedException
     * @throws MissingProcessConfigurationException
     *
     * @return ProcessConfigurationInterface|null
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        if (!is_a($resourceClass, ProcessConfigurationInterface::class, true)) {
            throw new ResourceClassNotSupportedException('Resource class is not a ProcessConfigurationInterface');
        }
        if ('get' === $operationName) {
            return $this->processConfigurationRegistry->getProcessConfiguration($id);
        }

        throw new InvalidArgumentException("Operation '{$operationName}' is not supported");
    }
}
