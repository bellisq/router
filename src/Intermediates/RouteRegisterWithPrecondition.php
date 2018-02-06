<?php

namespace Bellisq\Router\Intermediates;

use Bellisq\Router\Capsules\HostCapsule;
use Bellisq\Router\Capsules\MethodCapsule;
use Bellisq\Router\Capsules\PortCapsule;
use Bellisq\Router\Capsules\PreconditionCapsule;
use Bellisq\Router\Capsules\RouteRuleCapsule;
use Bellisq\Router\Capsules\SchemeCapsule;
use Bellisq\Router\Containers\RoutePreconditionsContainer;
use Bellisq\Router\Containers\RoutesContainer;
use Strict\Property\Intermediate\PropertyRegister;
use Strict\Property\Utility\StrictPropertyContainer;


/**
 * [Class] Route Register With Precondition (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 *
 * @property-read RouteRegisterInitial $or
 */
class RouteRegisterWithPrecondition
    extends StrictPropertyContainer
{
    /** @var RoutesContainer */
    private $container;

    /** @var PreconditionCapsule[] */
    private $conditions = [];

    /** @var PreconditionCapsule */
    private $currentCondition;

    /**
     * RouteRegisterWithPrecondition constructor.
     *
     * @param RoutesContainer       $container
     * @param PreconditionCapsule   $current
     * @param PreconditionCapsule[] ...$conditions
     */
    public function __construct(RoutesContainer $container, PreconditionCapsule $current, PreconditionCapsule ...$conditions)
    {
        parent::__construct();
        $this->container = $container;
        $this->conditions = $conditions;
        $this->currentCondition = $current;
    }

    protected function registerProperty(PropertyRegister $propertyRegister): void
    {
        $propertyRegister
            ->newVirtualProperty('or', function (): RouteRegisterInitial {
                return $this->or();
            }, null);
    }

    /**
     * @param string[] ...$methods
     * @return RouteRegisterWithPrecondition
     */
    public function forMethod(string ...$methods): RouteRegisterWithPrecondition
    {
        $ret = new RouteRegisterWithPrecondition($this->container, $this->currentCondition, ...$this->conditions);

        $na = [];
        foreach ($methods as $method) {
            $na[] = new MethodCapsule($method);
        }
        $ret->currentCondition = $ret->currentCondition->withMethods(...$na);

        return $ret;
    }

    /**
     * @param string[] ...$schemes
     * @return RouteRegisterWithPrecondition
     */
    public function forScheme(string ...$schemes): RouteRegisterWithPrecondition
    {
        $ret = new RouteRegisterWithPrecondition($this->container, $this->currentCondition, ...$this->conditions);

        $na = [];
        foreach ($schemes as $scheme) {
            $na[] = new SchemeCapsule($scheme);
        }
        $ret->currentCondition = $ret->currentCondition->withSchemes(...$na);

        return $ret;
    }

    /**
     * @param string[] ...$hosts
     * @return RouteRegisterWithPrecondition
     */
    public function forHost(string ...$hosts): RouteRegisterWithPrecondition
    {
        $ret = new RouteRegisterWithPrecondition($this->container, $this->currentCondition, ...$this->conditions);

        $na = [];
        foreach ($hosts as $host) {
            $na[] = new HostCapsule($host);
        }
        $ret->currentCondition = $ret->currentCondition->withHosts(...$na);

        return $ret;
    }

    /**
     * @param int[] ...$ports
     * @return RouteRegisterWithPrecondition
     */
    public function forPort(int ...$ports): RouteRegisterWithPrecondition
    {
        $ret = new RouteRegisterWithPrecondition($this->container, $this->currentCondition, ...$this->conditions);

        $na = [];
        foreach ($ports as $port) {
            $na[] = new PortCapsule($port);
        }
        $ret->currentCondition = $ret->currentCondition->withPorts(...$na);

        return $ret;
    }

    /**
     * @return RouteRegisterInitial
     */
    public function or (): RouteRegisterInitial
    {
        $newConditions = $this->conditions;
        $newConditions[] = $this->currentCondition;
        return new RouteRegisterInitial($this->container, ...$newConditions);
    }

    /**
     * @param string $rule
     * @return RouteRegisterWithRule
     */
    public function route(string $rule): RouteRegisterWithRule
    {
        return new RouteRegisterWithRule(
            $this->container,
            new RoutePreconditionsContainer($this->currentCondition, ...$this->conditions),
            new RouteRuleCapsule($rule)
        );
    }
}