<?php

namespace Bellisq\Router\Tests\TestCases;

use Bellisq\MVC\ViewAbstract;
use Bellisq\Request\Request;
use Bellisq\Request\RequestMutable;
use Bellisq\Router\Capsules\HostCapsule;
use Bellisq\Router\Capsules\RoutePreconditionCapsule;
use Bellisq\Router\Capsules\RouteHandlerCapsule;
use Bellisq\Router\Capsules\RouteRuleCapsule;
use Bellisq\Router\Capsules\SchemeCapsule;
use Bellisq\Router\Containers\RoutePreconditionsContainer;
use Bellisq\Router\RouteObject;
use Bellisq\Router\RouteParameters;
use Bellisq\Router\RouteResult;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;


class ZZRouteObjectTest
    extends TestCase
{
    /** @var RouteObject */
    private $ro;

    public function setUp()
    {
        $this->ro = new RouteObject(
            new RoutePreconditionsContainer(
                (new RoutePreconditionCapsule)->withSchemes(new SchemeCapsule('http'))->withHosts(new HostCapsule('www.example.com')),
                (new RoutePreconditionCapsule)->withSchemes(new SchemeCapsule('https'))->withHosts(new HostCapsule('secure.example.com'))
            ),
            (new RouteRuleCapsule('/{:t1}/{:t2}'))->withConstraint('t1', '@^[a-z]{3}$@u'),
            new RouteHandlerCapsule(function (): ViewAbstract {
                return new ZZViewMock;
            })
        );

    }

    public function testGenerate()
    {
        $this->assertEquals('https://secure.example.com/zzz/lol',
            $this->ro->withScheme('https')->generateUri(['t1' => 'zzz', 't2' => 'lol']));

        $ro = new RouteObject(
            new RoutePreconditionsContainer(
                (new RoutePreconditionCapsule)->withSchemes(new SchemeCapsule('http'))->withHosts(new HostCapsule('www.example.com')),
                (new RoutePreconditionCapsule)->withSchemes(new SchemeCapsule('https'))->withHosts(new HostCapsule('secure.example.com'))
            ),
            (new RouteRuleCapsule('/')),
            new RouteHandlerCapsule(function (): ViewAbstract {
                return new ZZViewMock;
            })
        );

        $this->assertEquals('https://secure.example.com',
            $ro->withScheme('https')->generateUri(new RouteParameters([])));

        $this->expectException(InvalidArgumentException::class);
        $ro->withScheme('https')->generateUri(3);
    }

    public function testMatch()
    {
        $reqM = new RequestMutable([
            'REMOTE_ADDR' => '127.0.0.1',
            'REMOTE_PORT' => '334'
        ], [], [], [], []);

        $reqM->line->scheme = 'http';
        $reqM->line->port = 80;
        $reqM->line->host = 'www.example.com';
        $reqM->line->method = 'GET';
        $reqM->line->path = '/zzz/444';

        $this->assertInstanceOf(RouteResult::class, $result = $this->ro->match(new Request($reqM)));
        $this->assertEquals('zzz', $result->getParameters()->t1);
        $this->assertEquals('444', $result->getParameters()->t2);

        $reqM->line->scheme = 'https';

        $this->assertNull($this->ro->match(new Request($reqM)));

        $reqM->line->scheme = 'http';
        $reqM->line->path = '/zzz';

        $this->assertNull($this->ro->match(new Request($reqM)));
    }
}