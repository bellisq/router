<?php
/**
 * Created by PhpStorm.
 * User: 4kizuki
 * Date: 2018/02/07
 * Time: 9:24
 */

namespace Bellisq\Router\Tests\TestCases;


use Bellisq\MVC\ViewAbstract;
use Bellisq\Router\Capsules\HostCapsule;
use Bellisq\Router\Capsules\RouteHandlerCapsule;
use Bellisq\Router\Capsules\RoutePreconditionCapsule;
use Bellisq\Router\Capsules\RouteRuleCapsule;
use Bellisq\Router\Capsules\SchemeCapsule;
use Bellisq\Router\Containers\RoutePreconditionsContainer;
use Bellisq\Router\Containers\RoutesContainer;
use Bellisq\Router\Exceptions\RoutesAccessor\UndefinedRouteException;
use Bellisq\Router\RouteObject;
use Bellisq\Router\RoutesAccessor;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock;
use PHPUnit\Framework\TestCase;


class ZZRouteAccessorTest
    extends TestCase
{
    /** @var RoutesContainer */
    private $container;

    /** @var RouteObject */
    private $ro1;

    /** @var RouteObject */
    private $ro2;

    /** @var RoutesAccessor */
    private $ra;

    public function setUp()
    {
        $this->container = new RoutesContainer;

        $this->ro1 = new RouteObject(
            new RoutePreconditionsContainer(
                (new RoutePreconditionCapsule)->withSchemes(new SchemeCapsule('http'))->withHosts(new HostCapsule('www.example.com')),
                (new RoutePreconditionCapsule)->withSchemes(new SchemeCapsule('https'))->withHosts(new HostCapsule('secure.example.com'))
            ),
            (new RouteRuleCapsule('/{:t1}/{:t2}'))->withConstraint('t1', '@^[a-z]{3}$@u'),
            new RouteHandlerCapsule(function (): ViewAbstract {
                return new ZZViewMock;
            })
        );

        $this->ro2 = new RouteObject(
            new RoutePreconditionsContainer(
                (new RoutePreconditionCapsule)->withSchemes(new SchemeCapsule('http'))->withHosts(new HostCapsule('www.example.com')),
                (new RoutePreconditionCapsule)->withSchemes(new SchemeCapsule('https'))->withHosts(new HostCapsule('secure.example.com'))
            ),
            (new RouteRuleCapsule('/api/{:t1}/{:t2}'))->withConstraint('t1', '@^[a-z]{3}$@u'),
            new RouteHandlerCapsule(function (): ViewAbstract {
                return new ZZViewMock;
            })
        );

        $this->container->addRoute('www', $this->ro1);
        $this->container->addRoute('api', $this->ro2);

        $this->ra = new RoutesAccessor($this->container);
    }

    public function testBehavior()
    {
        $this->assertEquals('http://www.example.com/xxx/lol', $this->ra->get('www')->withScheme('http')->generateUri(['t1' => 'xxx', 't2' => 'lol']));
        $this->assertEquals('http://www.example.com/api/xxx/lol', $this->ra->get('api')->withScheme('http')->generateUri(['t1' => 'xxx', 't2' => 'lol']));
    }

    public function testError()
    {
        $this->expectException(UndefinedRouteException::class);
        $this->ra->get('xxx');
    }
}