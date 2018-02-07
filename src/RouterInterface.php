<?php

namespace Bellisq\Router;

use Bellisq\Request\Request;


/**
 * [Interface] Router Interface
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
interface RouterInterface
{
    /**
     * Route requests.
     *
     * @param Request $request
     * @return RouteResult
     */
    public function route(Request $request): RouteResult;

    /**
     * @return RoutesAccessor
     */
    public function getAccessor(): RoutesAccessor;
}