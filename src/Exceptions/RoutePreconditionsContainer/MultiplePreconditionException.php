<?php

namespace Bellisq\Router\Exceptions\RoutePreconditionsContainer;

use LogicException;


/**
 * [Exception] Multiple Precondition
 *
 * To generate uri, precondition must be specified by with*** methods.
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class MultiplePreconditionException
    extends LogicException
{

}