<?php

namespace Bellisq\Router\Intermediates;

use Bellisq\Router\Capsules\RoutePreconditionCapsule;
use Bellisq\Router\Containers\RoutesContainer;


/**
 * [Class] Route Register Initial (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 * 
 * @internal
 */
class RouteRegisterInitial
{
    /** @var RoutesContainer */
    protected $container;

    /** @var RoutePreconditionCapsule[] */
    protected $conditions;

    /**
     * RouteRegisterInitial constructor.
     *
     * @param RoutesContainer            $container
     * @param RoutePreconditionCapsule[] ...$conditions
     */
    public function __construct(RoutesContainer $container, RoutePreconditionCapsule ...$conditions)
    {
        $this->container = $container;
        $this->conditions = $conditions;
    }

    /**
     * @param string[] ...$methods
     * @return RouteRegisterWithPrecondition
     */
    public function forMethod(string ...$methods): RouteRegisterWithPrecondition
    {
        return $this->createRRWRCopy()->forMethod(...$methods);
    }

    /**
     * @param string[] ...$schemes
     * @return RouteRegisterWithPrecondition
     */
    public function forScheme(string ...$schemes): RouteRegisterWithPrecondition
    {
        return $this->createRRWRCopy()->forScheme(...$schemes);
    }

    /**
     * @param string[] ...$hosts
     * @return RouteRegisterWithPrecondition
     */
    public function forHost(string ...$hosts): RouteRegisterWithPrecondition
    {
        return $this->createRRWRCopy()->forHost(...$hosts);
    }

    /**
     * @param int[] ...$ports
     * @return RouteRegisterWithPrecondition
     */
    public function forPort(int ...$ports): RouteRegisterWithPrecondition
    {
        return $this->createRRWRCopy()->forPort(...$ports);
    }

    /**
     * @return RouteRegisterWithPrecondition
     */
    private function createRRWRCopy(): RouteRegisterWithPrecondition
    {
        return new RouteRegisterWithPrecondition($this->container, new RoutePreconditionCapsule, ...$this->conditions);
    }
}