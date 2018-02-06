<?php

namespace Bellisq\Router\Containers;

use Bellisq\Request\Request;
use Bellisq\Router\Capsules\HostCapsule;
use Bellisq\Router\Capsules\MethodCapsule;
use Bellisq\Router\Capsules\PortCapsule;
use Bellisq\Router\Capsules\PreconditionCapsule;
use Bellisq\Router\Capsules\SchemeCapsule;
use Bellisq\Router\Exceptions\RoutePreconditionsContainer\MultiplePreconditionException;


/**
 * [Class] Route Preconditions Container (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RoutePreconditionsContainer
{
    /** @var PreconditionCapsule[] */
    private $preconditionCapsules;

    /**
     * RoutePreconditionsContainer constructor.
     *
     * @param PreconditionCapsule   $primaryPrecondition
     * @param PreconditionCapsule[] ...$preconditionCapsules
     */
    public function __construct(PreconditionCapsule $primaryPrecondition, PreconditionCapsule ...$preconditionCapsules)
    {
        array_unshift($preconditionCapsules, $primaryPrecondition);

        // TODO
        // convert
        //   [
        //     [[GET, POST],  [https], [example.com], [443]]
        //     [[POST], [https], [example.com], [443]]
        //     [[POST], [http],  [example.com], [443]]
        //   ]
        // into
        //   [
        //     [[GET, POST], [https], [example.com], [443]]
        //   ]

        $this->preconditionCapsules = $preconditionCapsules;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isSatisfiedWith(Request $request): bool
    {
        foreach ($this->preconditionCapsules as $preconditionCapsule) {
            if ($preconditionCapsule->isSatisfiedWith($request)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     *
     * @throws MultiplePreconditionException
     */
    public function generateUri(): string
    {
        $c = count($this->preconditionCapsules);
        assert($c >= 1);

        if (count($this->preconditionCapsules) !== 1) {
            throw new MultiplePreconditionException;
        }
        return $this->preconditionCapsules[0]->generateUri();
    }

    /**
     * @param SchemeCapsule $scheme
     * @return RoutePreconditionsContainer
     */
    public function withScheme(SchemeCapsule $scheme): self
    {
        $ret = clone $this;
        $na = [];

        foreach ($this->preconditionCapsules as $preconditionCapsule) {
            $r = $preconditionCapsule->restrictScheme($scheme);
            if (!is_null($r)) {
                $na[] = $r;
            }
        }

        if (count($na) === 0) {
            foreach ($this->preconditionCapsules as $preconditionCapsule) {
                $na[] = $preconditionCapsule->withSchemes($scheme);
            }
        }

        $ret->preconditionCapsules = $na;
        return $ret;
    }

    /**
     * @param HostCapsule $host
     * @return RoutePreconditionsContainer
     */
    public function withHost(HostCapsule $host): self
    {
        $ret = clone $this;
        $na = [];

        foreach ($this->preconditionCapsules as $preconditionCapsule) {
            $r = $preconditionCapsule->restrictHost($host);
            if (!is_null($r)) {
                $na[] = $r;
            }
        }

        if (count($na) === 0) {
            foreach ($this->preconditionCapsules as $preconditionCapsule) {
                $na[] = $preconditionCapsule->withHosts($host);
            }
        }

        $ret->preconditionCapsules = $na;
        return $ret;
    }

    /**
     * @param PortCapsule $port
     * @return RoutePreconditionsContainer
     */
    public function withPort(PortCapsule $port): self
    {
        $ret = clone $this;
        $na = [];

        foreach ($this->preconditionCapsules as $preconditionCapsule) {
            $r = $preconditionCapsule->restrictPort($port);
            if (!is_null($r)) {
                $na[] = $r;
            }
        }

        if (count($na) === 0) {
            foreach ($this->preconditionCapsules as $preconditionCapsule) {
                $na[] = $preconditionCapsule->withPorts($port);
            }
        }

        $ret->preconditionCapsules = $na;
        return $ret;
    }

    /**
     * @param MethodCapsule $method
     * @return RoutePreconditionsContainer
     */
    public function withMethod(MethodCapsule $method): self
    {
        $ret = clone $this;
        $na = [];

        foreach ($this->preconditionCapsules as $preconditionCapsule) {
            $r = $preconditionCapsule->restrictMethod($method);
            if (!is_null($r)) {
                $na[] = $r;
            }
        }

        if (count($na) === 0) {
            foreach ($this->preconditionCapsules as $preconditionCapsule) {
                $na[] = $preconditionCapsule->withMethods($method);
            }
        }

        $ret->preconditionCapsules = $na;
        return $ret;
    }
}