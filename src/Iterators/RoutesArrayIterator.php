<?php

namespace Bellisq\Router\Iterators;

use ArrayIterator;
use Bellisq\Router\RouteObject;


/**
 * [Class] Routes Array Iterator (Mutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RoutesArrayIterator
    extends ArrayIterator
{
    /**
     * @inheritdoc
     *
     * @return RouteObject
     */
    public function current(): RouteObject
    {
        return parent::current();
    }
}