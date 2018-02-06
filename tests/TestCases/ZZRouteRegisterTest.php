<?php

namespace Bellisq\Router\Tests\TestCases;

use Bellisq\MVC\ViewAbstract;
use Bellisq\Router\Containers\RoutesContainer;
use Bellisq\Router\RouteRegister;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock;
use PHPUnit\Framework\TestCase;


class ZZRouteRegisterTest
    extends TestCase
{
    public function testBehavior()
    {
        $rc = new RoutesContainer;
        $rrg = new RouteRegister($rc);

        $rrg->forMethod('GET', 'POST')
            ->forScheme('https')
            ->forPort(443, 8443)
            ->or
            ->forMethod('PUT')
            ->forScheme('http')
            ->forPort(80, 8080)
            ->route('/x')
            ->handler = function (): ViewAbstract {
            return new ZZViewMock;
        };

        $this->assertEquals(
            'https://example.com:8443/x',
            $rc[0]
                ->withHost('example.com')
                ->withPort('8443')
                ->generateUri([])
        );

        $rrg->route('/')
            ->handler = function (): ViewAbstract {
            return new ZZViewMock;
        };

        $this->assertEquals(
            'http://example.jp',
            $rc[1]
                ->withScheme('http')
                ->withHost('example.jp')
                ->generateUri([])
        );
    }
}