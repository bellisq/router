<?php

namespace Bellisq\Router;

use Bellisq\Router\Capsules\PreconditionCapsule;
use Bellisq\Router\Capsules\RouteRuleCapsule;
use Bellisq\Router\Containers\RoutePreconditionsContainer;
use Bellisq\Router\Containers\RoutesContainer;
use Bellisq\Router\Intermediates\RouteRegisterInitial;
use Bellisq\Router\Intermediates\RouteRegisterWithRule;


/**
 * [Class] Route Register (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RouteRegister
    extends RouteRegisterInitial
{
    /**
     * RouteRegister constructor.
     *
     * @param RoutesContainer $container
     */
    public function __construct(RoutesContainer $container)
    {
        parent::__construct($container);
    }

    /**
     * @param string $rule
     * @return RouteRegisterWithRule
     */
    public function route(string $rule): RouteRegisterWithRule
    {
        return new RouteRegisterWithRule(
            $this->container,
            new RoutePreconditionsContainer(new PreconditionCapsule),
            new RouteRuleCapsule($rule)
        );
    }
}