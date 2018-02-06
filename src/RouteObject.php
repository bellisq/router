<?php

namespace Bellisq\Router;

use Bellisq\Request\Request;
use Bellisq\Router\Capsules\HostCapsule;
use Bellisq\Router\Capsules\PortCapsule;
use Bellisq\Router\Capsules\RouteHandlerCapsule;
use Bellisq\Router\Capsules\RouteRuleCapsule;
use Bellisq\Router\Capsules\SchemeCapsule;
use Bellisq\Router\Containers\RoutePreconditionsContainer;
use InvalidArgumentException;


/**
 * [Class] Route Object (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RouteObject
{
    private $preconditionsContainer;
    private $ruleCapsule;
    private $handlerCapsule;

    /**
     * RouteObject constructor.
     *
     * @param RoutePreconditionsContainer $preconditionsContainer
     * @param RouteRuleCapsule            $ruleCapsule
     * @param RouteHandlerCapsule         $handlerCapsule
     */
    public function __construct(
        RoutePreconditionsContainer $preconditionsContainer,
        RouteRuleCapsule $ruleCapsule,
        RouteHandlerCapsule $handlerCapsule
    ) {
        $this->preconditionsContainer = $preconditionsContainer;
        $this->ruleCapsule = $ruleCapsule;
        $this->handlerCapsule = $handlerCapsule;
    }

    /**
     * @param array|RouteParameters $params
     * @return string
     */
    public function generateUri($params): string
    {
        if (!($params instanceof RouteParameters)) {
            if (!is_array($params)) {
                throw new InvalidArgumentException('Argument 1 passed to ' . __METHOD__ . '() must be of the type array or ' . RouteParameters::class . ', ' . gettype($params) . ' given.');
            }
            $params = new RouteParameters($params);
        }

        return rtrim($this->preconditionsContainer->generateUri() . $this->ruleCapsule->generatePath($params), '/');
    }

    /**
     * @param Request $request
     * @return RouteResult|null
     */
    public function match(Request $request): ?RouteResult
    {
        if (!$this->preconditionsContainer->isSatisfiedWith($request)) {
            return null;
        }

        $routeParameters = $this->ruleCapsule->match($request->line->path);
        if (is_null($routeParameters)) {
            return null;
        }

        return new RouteResult($this->handlerCapsule, $routeParameters);
    }

    /**
     * @param string $scheme
     * @return RouteObject
     */
    public function withScheme(string $scheme): self
    {
        $ret = clone $this;
        $ret->preconditionsContainer = $ret->preconditionsContainer->withScheme(new SchemeCapsule($scheme));
        return $ret;
    }

    /**
     * @param string $host
     * @return RouteObject
     */
    public function withHost(string $host): self
    {
        $ret = clone $this;
        $ret->preconditionsContainer = $ret->preconditionsContainer->withHost(new HostCapsule($host));
        return $ret;
    }

    /**
     * @param int $port
     * @return RouteObject
     */
    public function withPort(int $port): self
    {
        $ret = clone $this;
        $ret->preconditionsContainer = $ret->preconditionsContainer->withPort(new PortCapsule($port));
        return $ret;
    }
}