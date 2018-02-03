<?php

namespace Bellisq\Router\Exceptions\RouteParameters;

use Bellisq\Router\Exceptions\RouteParameters\InvalidInitializerException;


/**
 * [Exception] String-inconvertible Initializer Value
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class NonStringValueException
    extends InvalidInitializerException
{
    /**
     * NonStringValueException constructor.
     */
    public function __construct()
    {
        parent::__construct('Any element of the initializer of the Parameters must be a type of string or convertible to string.');
    }
}