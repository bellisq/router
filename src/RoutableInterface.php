<?php

namespace Bellisq\Router;

use Bellisq\Router\Intermediates\RouteRegisterWithRule;


/**
 * [Interface] Routable Interface
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.2.0
 */
interface RoutableInterface
{
    /**
     * @param string $rule
     * @return RouteRegisterWithRule
     */
    public function route(string $rule): RouteRegisterWithRule;
}