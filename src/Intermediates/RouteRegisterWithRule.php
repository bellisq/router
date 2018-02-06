<?php

namespace Bellisq\Router\Intermediates;

use Bellisq\Router\Intermediates\RouteHandlerRegister;


/**
 * [Class] RouteRegisterWithRule (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
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
     */
    public function withConstraint(string $paramName, string $regex): self
    {
        $ret = clone $this;
        $ret->routeRuleCapsule = $this->routeRuleCapsule->withConstraint($paramName, $regex);
        return $ret;
    }
}