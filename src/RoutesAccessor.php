<?php

namespace Bellisq\Router;

use Bellisq\Router\Containers\RoutesContainer;
use Bellisq\Router\Exceptions\RoutesAccessor\UndefinedRouteException;


/**
 * [Class] Routes Accessor
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RoutesAccessor
{
    /** @var RoutesContainer */
    private $container;

    /**
     * RoutesAccessor constructor.
     *
     * @param RoutesContainer $routesContainer
     */
    public function __construct(RoutesContainer $routesContainer)
    {
        $this->container = $routesContainer;
    }

    /**
     * @param string $name
     * @return RouteObject
     *
     * @throws UndefinedRouteException
     */
    public function get(string $name): RouteObject
    {
        if ($this->container->offsetExists($name)) {
            return $this->container->offsetGet($name);
        } else {
            throw new UndefinedRouteException;
        }
    }
}