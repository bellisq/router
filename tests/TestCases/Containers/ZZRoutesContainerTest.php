<?php

namespace Bellisq\Router\Tests\TestCases\Containers;

use BadMethodCallException;
use Bellisq\MVC\ViewAbstract;
use Bellisq\Router\Capsules\HostCapsule;
use Bellisq\Router\Capsules\RoutePreconditionCapsule;
use Bellisq\Router\Capsules\RouteHandlerCapsule;
use Bellisq\Router\Capsules\RouteRuleCapsule;
use Bellisq\Router\Capsules\SchemeCapsule;
use Bellisq\Router\Containers\RoutePreconditionsContainer;
use Bellisq\Router\Containers\RoutesContainer;
use Bellisq\Router\Exceptions\RoutesContainer\DuplicateRouteNameException;
use Bellisq\Router\RouteObject;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock;
use PHPUnit\Framework\TestCase;


class ZZRoutesContainerTest
    extends TestCase
{
    /** @var RoutesContainer */
    private $container;

    /** @var RouteObject */
    private $ro1;

    /** @var RouteObject */
    private $ro2;

    public function setUp()
    {
        $this->container = new RoutesContainer;

        $this->ro1 = new RouteObject(
            new RoutePreconditionsContainer(
                (new RoutePreconditionCapsule)->withSchemes(new SchemeCapsule('http'))->withHosts(new HostCapsule('www.example.com')),
                (new RoutePreconditionCapsule)->withSchemes(new SchemeCapsule('https'))->withHosts(new HostCapsule('secure.example.com'))
            ),
            (new RouteRuleCapsule('/{:t1}/{:t2}'))->withRegexConstraint('t1', '@^[a-z]{3}$@u'),
            new RouteHandlerCapsule(function (): ViewAbstract {
                return new ZZViewMock();
            })
        );

        $this->ro2 = new RouteObject(
            new RoutePreconditionsContainer(
                (new RoutePreconditionCapsule)->withSchemes(new SchemeCapsule('http'))->withHosts(new HostCapsule('www.example.com')),
                (new RoutePreconditionCapsule)->withSchemes(new SchemeCapsule('https'))->withHosts(new HostCapsule('secure.example.com'))
            ),
            (new RouteRuleCapsule('/api/{:t1}/{:t2}'))->withRegexConstraint('t1', '@^[a-z]{3}$@u'),
            new RouteHandlerCapsule(function (): ViewAbstract {
                return new ZZViewMock();
            })
        );
    }

    public function testBehavior()
    {
        $this->container->addRoute(null, $this->ro1);
        $this->container->addRoute('api', $this->ro2);

        $n = [];
        foreach ($this->container as $value) {
            $n[] = $value;
        }

        $this->assertTrue(2 === count($this->container));
        $this->assertTrue(2 === count($n));
        $this->assertTrue($n[0] === $this->ro1);
        $this->assertTrue($this->container[0] === $this->ro1);
        $this->assertTrue($this->container['api'] === $this->ro2);
        $this->assertTrue(isset($this->container[0]));
        $this->assertTrue(isset($this->container['api']));
        $this->assertFalse(isset($this->container['www']));
    }

    public function testSet()
    {
        $this->expectException(BadMethodCallException::class);
        $this->container['t'] = 3;
    }

    public function testUnset()
    {
        $this->expectException(BadMethodCallException::class);
        unset($this->container['t']);
    }

    public function testDuplicate()
    {
        $this->expectException(DuplicateRouteNameException::class);
        $this->container->addRoute('api', $this->ro1);
        $this->container->addRoute('api', $this->ro2);
    }
}