<?php

namespace Bellisq\Router\Containers;

use ArrayAccess;
use BadMethodCallException;
use Bellisq\Router\Iterators\RoutesArrayIterator;
use Bellisq\Router\RouteObject;
use Countable;
use IteratorAggregate;


/**
 * [Class] Routes Container (Mutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RoutesContainer
    implements IteratorAggregate, ArrayAccess, Countable
{
    /** @var RouteObject[] */
    private $routes = [];

    /**
     * @param null|string $name
     * @param RouteObject $routeObject
     */
    public function addRoute(?string $name, RouteObject $routeObject): void
    {
        if (is_null($name)) {
            $this->routes[] = $routeObject;
        } else {
            $this->routes[$name] = $routeObject;
        }
    }

    /**
     * Implementation of Countable::count()
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->routes);
    }

    /**
     * Implementation of IteratorAggregate::getIterator()
     *
     * @return RoutesArrayIterator
     */
    public function getIterator(): RoutesArrayIterator
    {
        return new RoutesArrayIterator($this->routes);
    }

    /**
     * Implementation of ArrayAccess::offsetSet()
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @throws BadMethodCallException
     */
    public function offsetSet($offset, $value): void
    {
        throw new BadMethodCallException;
    }

    /**
     * Implementation of ArrayAccess::offsetGet()
     *
     * @param mixed $offset
     * @return RouteObject
     */
    public function offsetGet($offset): RouteObject
    {
        return $this->routes[$offset];
    }

    /**
     * Implementation of ArrayAccess::offsetGet()
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->routes[$offset]);
    }

    /**
     * Implementation of ArrayAccess::offsetGet()
     *
     * @param mixed $offset
     *
     * @throws BadMethodCallException
     */
    public function offsetUnset($offset): void
    {
        throw new BadMethodCallException;
    }
}