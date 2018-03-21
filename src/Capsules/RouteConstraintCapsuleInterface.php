<?php

namespace Bellisq\Router\Capsules;

use Bellisq\Router\Exceptions\RouteConstraint\ConstraintViolationException;
use Bellisq\Router\RouteParameters;


/**
 * [Interface] Route Constraint Capsule
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.4.0
 *
 * @internal
 */
interface RouteConstraintCapsuleInterface
{
    /**
     * @param RouteParameters $params
     * @return bool
     */
    public function isSatisfiedWith(RouteParameters $params): bool;

    /**
     * @param RouteParameters $params
     *
     * @throws ConstraintViolationException
     */
    public function satisfiedOrFail(RouteParameters $params): void;
}