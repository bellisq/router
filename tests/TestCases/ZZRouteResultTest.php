<?php

namespace Bellisq\Router\Tests\TestCases;

use Bellisq\MVC\ViewAbstract;
use Bellisq\Router\Capsules\RouteHandlerCapsule;
use Bellisq\Router\RouteParameters;
use Bellisq\Router\RouteResult;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock;
use PHPUnit\Framework\TestCase;


class ZZRouteResultTest
    extends TestCase
{
    public function testBehavior()
    {
        $rr = new RouteResult(
            new RouteHandlerCapsule(function (): ViewAbstract {
                return new ZZViewMock;
            }),
            new RouteParameters([])
        );

        $this->assertInstanceOf(ZZViewMock::class, ($rr->getHandler())());
        $this->assertInstanceOf(RouteParameters::class, $rr->getParameters());
    }
}