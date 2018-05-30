<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle\Factory;

use CleverAge\ProcessLauncherBundle\Process\ProcessInfo;

/**
 * Creates ProcessInfo objects from array returned by the PsUtility class
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class ProcessInfoFactory
{
    /** @var \ReflectionClass */
    protected $reflection;
    /** @var string */
    protected $className = ProcessInfo::class;

    /** @var array */
    protected $propertyNameReplacements = [
        '%' => '',
    ];

    /**
     * @param string $className
     * @param array  $propertyNameReplacements
     *
     * @throws \RuntimeException
     */
    public function __construct(string $className = null, array $propertyNameReplacements = null)
    {
        if (null !== $className) {
            $this->className = $className;
        }
        if (null !== $propertyNameReplacements) {
            $this->propertyNameReplacements = $propertyNameReplacements;
        }

        try {
            $this->reflection = new \ReflectionClass($this->className);
        } catch (\ReflectionException $e) {
            throw new \RuntimeException("Unknown class {$this->className}", 0, $e);
        }
    }

    /**
     * @param array $processInfo
     *
     * @throws \RuntimeException
     *
     * @return ProcessInfo
     */
    public function create(array $processInfo)
    {
        /** @var ProcessInfo $processInfoObject */
        $processInfoObject = $this->reflection->newInstanceWithoutConstructor();
        foreach ($processInfo as $key => $value) {
            $this->parseProperty($processInfoObject, $key, $value);
        }

        return $processInfoObject;
    }

    /**
     * @param object $processInfoObject
     * @param string $key
     * @param mixed  $value
     */
    protected function parseProperty($processInfoObject, string $key, $value)
    {
        $propertyName = strtolower(strtr($key, $this->propertyNameReplacements));
        $reflMethod = $this->reflection->getMethod('get'.ucfirst($propertyName));
        $returnType = $reflMethod->getReturnType();
        if ($returnType) {
            if ($returnType->isBuiltin()) {
                settype($value, $returnType->getName());
            } else {
                // @todo denormalize value ?
            }
        }
        $reflProperty = $this->reflection->getProperty($propertyName);
        $reflProperty->setAccessible(true);
        $reflProperty->setValue($processInfoObject, $value);
    }
}
