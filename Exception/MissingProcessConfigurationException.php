<?php
/*
 * This file is part of the CleverAge/ProcessLauncherBundle package.
 *
 * Copyright (c) 2015-2018 Clever-Age
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CleverAge\ProcessLauncherBundle\Exception;

/**
 * Thrown when trying to access a non-existent process configurations
 *
 * @author Vincent Chalnot <vchalnot@clever-age.com>
 */
class MissingProcessConfigurationException extends \UnexpectedValueException implements ProcessExceptionInterface
{
    /**
     * @param string $code
     *
     * @return MissingProcessConfigurationException
     */
    public static function create(string $code)
    {
        return new self("No process configuration with code : {$code}");
    }
}
