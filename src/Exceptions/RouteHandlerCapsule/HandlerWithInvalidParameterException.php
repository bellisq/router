<?php

namespace Bellisq\Router\Exceptions\RouteHandlerCapsule;

use Bellisq\Router\Exceptions\RouteHandlerCapsule\UnqualifiedHandlerException;


/**
 * [Exception] Handler with Invalid Parameter
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class HandlerWithInvalidParameterException
    extends UnqualifiedHandlerException
{
    /**
     * HandlerWithInvalidParameterException constructor.
     */
    public function __construct()
    {
        parent::__construct('Any argument of a handler must not be variadic, must have a non-scalar type-hint.');
    }
}