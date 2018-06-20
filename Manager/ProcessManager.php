<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle\Manager;

use CleverAge\ProcessLauncherBundle\Entity\ProcessExecution;
use CleverAge\ProcessLauncherBundle\Factory\ProcessInfoFactory;
use CleverAge\ProcessLauncherBundle\Process\ProcessConfigurationInterface;
use CleverAge\ProcessLauncherBundle\Process\ProcessExecutionInterface;
use CleverAge\ProcessLauncherBundle\Process\ProcessInfo;
use CleverAge\ProcessLauncherBundle\Utility\PsUtility;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Process\Process;

/**
 * Manage, launch and track processes
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class ProcessManager
{
    /** @var ManagerRegistry */
    protected $doctrine;

    /** @var PsUtility */
    protected $psUtility;

    /** @var ProcessInfoFactory */
    protected $processInfoFactory;

    /**
     * @param ManagerRegistry    $doctrine
     * @param PsUtility          $psUtility
     * @param ProcessInfoFactory $processInfoFactory
     */
    public function __construct(ManagerRegistry $doctrine, PsUtility $psUtility, ProcessInfoFactory $processInfoFactory)
    {
        $this->doctrine = $doctrine;
        $this->psUtility = $psUtility;
        $this->processInfoFactory = $processInfoFactory;
    }

    /**
     * @param ProcessConfigurationInterface $processConfiguration
     *
     * @throws \UnexpectedValueException
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     *
     * @return ProcessExecutionInterface
     */
    public function launchProcess(ProcessConfigurationInterface $processConfiguration): ProcessExecutionInterface
    {
        $process = new Process(
            $processConfiguration->getCommand(),
            $processConfiguration->getWorkingDirectory(),
            null,
            $processConfiguration->getInput()
        );
        $process->disableOutput();
        $process->start();

        // Fetching real process PID:
        $pid = exec('pgrep -P '.$process->getPid());
        // https://github.com/symfony/symfony/issues/5759
        // https://bugs.php.net/bug.php?id=39992

        $processExecution = new ProcessExecution($processConfiguration, $pid);
        $this->saveProcessExecution($processExecution);

        return $processExecution;
    }

    /**
     * @param ProcessExecutionInterface $processExecution
     *
     * @throws \RuntimeException
     *
     * @return ProcessInfo|null
     */
    public function trackProcess(ProcessExecutionInterface $processExecution)
    {
        $rawInfo = $this->psUtility->getProcessInfo($processExecution->getPid());
        if (false === $rawInfo) {
            $processExecution->setFinished(true);
            $this->saveProcessExecution($processExecution);

            return null;
        }

        return $this->processInfoFactory->create($rawInfo);
    }

    /**
     * @param ProcessExecutionInterface $processExecution
     *
     * @throws \UnexpectedValueException
     */
    protected function saveProcessExecution(ProcessExecutionInterface $processExecution)
    {
        $entityManager = $this->doctrine->getManagerForClass(ProcessExecution::class);
        if (!$entityManager instanceof EntityManagerInterface) {
            throw new \UnexpectedValueException('No manager found for class ProcessExecution');
        }
        $entityManager->persist($processExecution);
        $entityManager->flush();
    }
}
