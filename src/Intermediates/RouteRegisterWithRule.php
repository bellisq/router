<?php

namespace Bellisq\Router\Intermediates;

use Bellisq\Router\Intermediates\RouteHandlerRegister;
use Closure;


/**
 * [Class] RouteRegisterWithRule (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 *
 * @internal
 */
class RouteRegisterWithRule
    extends RouteHandlerRegister
{
    /**
     * @param string $name
     * @return RouteHandlerRegister
     */
    public function withName(string $name): RouteHandlerRegister
    {
        $ret = new RouteHandlerRegister(
            $this->routesContainer,
            $this->routePreconditionsContainer,
            $this->routeRuleCapsule
        );
        $ret->name = $name;
        return $ret;
    }

    /**
     * @param string $paramName
     * @param string $regex
     * @return RouteRegisterWithRule
     * @deprecated 1.4.0 use withRegexConstraint instead
     */
    public function withConstraint(string $paramName, string $regex): self
    {
        return $this->withRegexConstraint($paramName, $regex);
    }

    /**
     * @param string $paramName
     * @param string $regex
     * @return RouteRegisterWithRule
     */
    public function withRegexConstraint(string $paramName, string $regex): self
    {
        $ret = clone $this;
        $ret->routeRuleCapsule = $this->routeRuleCapsule->withRegexConstraint($paramName, $regex);
        return $ret;
    }

    /**
     * @param Closure $closure
     * @return RouteRegisterWithRule
     *
     * @since 1.4.0
     */
    public function withClosureConstraint(Closure $closure): self
    {
        $ret = clone $this;
        $ret->routeRuleCapsule = $this->routeRuleCapsule->withClosureConstraint($closure);
        return $ret;
    }
}