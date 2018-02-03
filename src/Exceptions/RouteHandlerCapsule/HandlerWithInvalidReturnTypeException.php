<?php

namespace Bellisq\Router\Exceptions\RouteHandlerCapsule;

use Bellisq\Router\Exceptions\RouteHandlerCapsule\UnqualifiedHandlerException;


/**
 * [Exception] Handler with Invalid Return Type
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class HandlerWithInvalidReturnTypeException
    extends UnqualifiedHandlerException
{
    /**
     * HandlerWithInvalidReturnTypeException constructor.
     */
    public function __construct()
    {
        parent::__construct('A handler must have return-value type-hint of ViewAbstract.');
    }
}