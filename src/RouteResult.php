<?php

namespace Bellisq\Router;

use Bellisq\Router\Capsules\RouteHandlerCapsule;
use Bellisq\Router\RouteParameters;
use Closure;


/**
 * [Class] Route Result (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RouteResult
{
    /** @var RouteHandlerCapsule */
    private $routeHandlerCapsule;

    /** @var RouteParameters */
    private $routeParameters;

    /**
     * RouteResult constructor.
     *
     * @param RouteHandlerCapsule $routeHandlerCapsule
     * @param RouteParameters     $routeParameters
     */
    public function __construct(
        RouteHandlerCapsule $routeHandlerCapsule,
        RouteParameters $routeParameters
    ) {
        $this->routeHandlerCapsule = $routeHandlerCapsule;
        $this->routeParameters = $routeParameters;
    }

    /**
     * @return Closure
     */
    public function getHandler(): Closure
    {
        return $this->routeHandlerCapsule->getHandler();
    }

    /**
     * @return RouteParameters
     */
    public function getParameters(): RouteParameters
    {
        return $this->routeParameters;
    }
}