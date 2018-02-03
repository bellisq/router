<?php

namespace Bellisq\Router\Exceptions\RouteParameters;

use Bellisq\Router\Exceptions\RouteParameters\InvalidInitializerException;


/**
 * [Exception] Invalid Initializer Key
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class InvalidKeyException
    extends InvalidInitializerException
{
    /**
     * InvalidKeyException constructor.
     */
    public function __construct()
    {
        parent::__construct('The initializer of the Parameters must be an associative array with string keys.');
    }
}