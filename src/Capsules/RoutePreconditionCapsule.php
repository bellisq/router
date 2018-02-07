<?php

namespace Bellisq\Router\Capsules;

use Bellisq\Request\Request;
use Bellisq\Router\Exceptions\RoutePreconditionCapsule\MultipleCandidateException;


/**
 * [Class] Route Precondition Capsule (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RoutePreconditionCapsule
{
    /** @var PortCapsule[] */
    private $ports = [];

    /** @var MethodCapsule[] */
    private $methods = [];

    /** @var SchemeCapsule[] */
    private $schemes = [];

    /** @var HostCapsule[] */
    private $hosts = [];

    /**
     * @param PortCapsule[] ...$ports
     * @return RoutePreconditionCapsule
     */
    public function withPorts(PortCapsule ...$ports): self
    {
        $ret = clone $this;
        $ret->ports = $ports;
        return $ret;
    }

    /**
     * @param MethodCapsule[] ...$methods
     * @return RoutePreconditionCapsule
     */
    public function withMethods(MethodCapsule ...$methods): self
    {
        $ret = clone $this;
        $ret->methods = $methods;
        return $ret;
    }

    /**
     * @param SchemeCapsule[] ...$schemes
     * @return RoutePreconditionCapsule
     */
    public function withSchemes(SchemeCapsule ...$schemes): self
    {
        $ret = clone $this;
        $ret->schemes = $schemes;
        return $ret;
    }

    /**
     * @param HostCapsule[] ...$hosts
     * @return RoutePreconditionCapsule
     */
    public function withHosts(HostCapsule ...$hosts): self
    {
        $ret = clone $this;
        $ret->hosts = $hosts;
        return $ret;
    }

    /**
     * @param MethodCapsule $newMethod
     * @return RoutePreconditionCapsule|null
     */
    public function restrictMethod(MethodCapsule $newMethod): ?self
    {
        if (empty($this->methods)) {
            return $this->withMethods($newMethod);
        }
        $m = $newMethod->getMethod();
        foreach ($this->methods as $method) {
            if ($method->getMethod() === $m) {
                return $this->withMethods($newMethod);
            }
        }
        return null;
    }

    /**
     * @param PortCapsule $newPort
     * @return RoutePreconditionCapsule|null
     */
    public function restrictPort(PortCapsule $newPort): ?self
    {
        if (empty($this->ports)) {
            return $this->withPorts($newPort);
        }
        $p = $newPort->getPort();
        foreach ($this->ports as $port) {
            if ($port->getPort() === $p) {
                return $this->withPorts($newPort);
            }
        }
        return null;
    }

    /**
     * @param SchemeCapsule $newScheme
     * @return RoutePreconditionCapsule|null
     */
    public function restrictScheme(SchemeCapsule $newScheme): ?self
    {
        if (empty($this->schemes)) {
            return $this->withSchemes($newScheme);
        }
        $s = $newScheme->getScheme();
        foreach ($this->schemes as $scheme) {
            if ($scheme->getScheme() === $s) {
                return $this->withSchemes($newScheme);
            }
        }
        return null;
    }

    /**
     * @param HostCapsule $newHost
     * @return RoutePreconditionCapsule|null
     */
    public function restrictHost(HostCapsule $newHost): ?self
    {
        if (empty($this->hosts)) {
            return $this->withHosts($newHost);
        }
        $h = $newHost->getHost();
        foreach ($this->hosts as $host) {
            if ($host->getHost() === $h) {
                return $this->withHosts($newHost);
            }
        }
        return null;
    }

    /**
     * @return string
     *
     * @throws MultipleCandidateException
     */
    public function generateUri(): string
    {
        if (count($this->schemes) !== 1) {
            throw new MultipleCandidateException('scheme');
        }
        $scheme = $this->schemes[0];

        if (count($this->hosts) !== 1) {
            throw new MultipleCandidateException('host');
        }
        $host = $this->hosts[0];

        $pc = count($this->ports);
        if ($pc === 0) {
            $port = new PortCapsule(
                $scheme->isHttp() ? Request::PORT_HTTP : Request::PORT_HTTPS
            );
        } else if ($pc !== 1) {
            throw new MultipleCandidateException('port');
        } else {
            $port = $this->ports[0];
        }

        if (($scheme->isHttp() && $port->getPort() === Request::PORT_HTTP)
            ||
            ($scheme->isHttps() && $port->getPort() === Request::PORT_HTTPS)) {
            $p = '';
        } else {
            $p = ':' . $port->getPort();
        }

        return ($scheme->getScheme()) . '://' . ($host->getHost()) . $p;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isSatisfiedWith(Request $request): bool
    {
        return $this->isPortSatisfied($request)
            && $this->isMethodSatisfied($request)
            && $this->isSchemeSatisfied($request)
            && $this->isHostSatisfied($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isPortSatisfied(Request $request): bool
    {
        if (empty($this->ports)) {
            return true;
        }
        foreach ($this->ports as $port) {
            if ($port->getPort() === $request->line->port) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isMethodSatisfied(Request $request): bool
    {
        if (empty($this->methods)) {
            return true;
        }
        foreach ($this->methods as $method) {
            if ($method->getMethod() === $request->line->method) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isSchemeSatisfied(Request $request): bool
    {
        if (empty($this->schemes)) {
            return true;
        }
        foreach ($this->schemes as $scheme) {
            if ($scheme->getScheme() === $request->line->scheme) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isHostSatisfied(Request $request): bool
    {
        if (empty($this->hosts)) {
            return true;
        }
        foreach ($this->hosts as $host) {
            if ($host->getHost() === $request->line->host) {
                return true;
            }
        }
        return false;
    }
}