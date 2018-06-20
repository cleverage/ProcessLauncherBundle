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
use CleverAge\ProcessLauncherBundle\Entity\ProcessExecution;
use CleverAge\ProcessLauncherBundle\Exception\MissingProcessConfigurationException;
use CleverAge\ProcessLauncherBundle\Manager\ProcessManager;
use CleverAge\ProcessLauncherBundle\Process\ProcessConfigurationInterface;
use CleverAge\ProcessLauncherBundle\Process\ProcessExecutionInterface;
use CleverAge\ProcessLauncherBundle\Process\ProcessInfo;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Provides access to process configuration registry through Api Platform.
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class ProcessInfoDataProvider implements CollectionDataProviderInterface, ItemDataProviderInterface
{
    /** @var ProcessManager */
    protected $processManager;

    /** @var EntityRepository */
    protected $repository;

    /**
     * @param ManagerRegistry $doctrine
     * @param ProcessManager  $processManager
     *
     * @throws \UnexpectedValueException
     */
    public function __construct(ManagerRegistry $doctrine, ProcessManager $processManager)
    {
        $this->processManager = $processManager;
        $entityManager = $doctrine->getManagerForClass(ProcessExecution::class);
        if (!$entityManager instanceof EntityManagerInterface) {
            throw new \UnexpectedValueException('No manager found for class ProcessExecution');
        }
        $this->repository = $entityManager->getRepository(ProcessExecution::class);
    }

    /**
     * Retrieves a collection.
     *
     * @param string      $resourceClass
     * @param string|null $operationName
     *
     * @throws \RuntimeException
     * @throws ResourceClassNotSupportedException
     *
     * @return ProcessInfo[]|PaginatorInterface|\Traversable
     */
    public function getCollection(string $resourceClass, string $operationName = null)
    {
        if (!is_a($resourceClass, ProcessInfo::class, true)) {
            throw new ResourceClassNotSupportedException('Resource class is not a ProcessInfo');
        }

        $results = [];
        /** @var ProcessExecutionInterface $processExecution */
        foreach ($this->repository->findBy(['finished' => false]) as $processExecution) {
            $processInfo = $this->processManager->trackProcess($processExecution);
            if ($processInfo) {
                $results[] = $processInfo;
            }
        }

        return $results;
    }

    /**
     * Retrieves an item.
     *
     * @param string      $resourceClass
     * @param int|string  $id
     * @param string|null $operationName
     * @param array       $context
     *
     * @throws \RuntimeException
     * @throws InvalidArgumentException
     * @throws ResourceClassNotSupportedException
     * @throws MissingProcessConfigurationException
     *
     * @return ProcessInfo|null
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        if (!is_a($resourceClass, ProcessInfo::class, true)) {
            throw new ResourceClassNotSupportedException('Resource class is not a ProcessConfigurationInterface');
        }
        if ('get' === $operationName) {
            /** @var ProcessExecutionInterface $processExecution */
            $processExecution = $this->repository->find($id);
            if (!$processExecution) {
                return null;
            }

            return $this->processManager->trackProcess($processExecution);
        }

        throw new InvalidArgumentException("Operation '{$operationName}' is not supported");
    }
}
