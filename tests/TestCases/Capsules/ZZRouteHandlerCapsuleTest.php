<?php

namespace Bellisq\Router\Tests\TestCases\Capsules;

use Bellisq\MVC\ViewAbstract;
use Bellisq\Router\Capsules\RouteHandlerCapsule;
use Bellisq\Router\Exceptions\RouteHandlerCapsule\HandlerWithInvalidParameterException;
use Bellisq\Router\Exceptions\RouteHandlerCapsule\HandlerWithInvalidReturnTypeException;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock;
use PHPUnit\Framework\TestCase;


class ZZRouteHandlerCapsuleTest
    extends TestCase
{
    public function testBehavior()
    {
        $rhc = new RouteHandlerCapsule(function (ZZViewMock $zvm): ViewAbstract {
            return $zvm;
        });

        $this->assertInstanceOf(ZZViewMock::class, ($rhc->getHandler())(new ZZViewMock));
    }

    public function testArgumentNoType()
    {
        $this->expectException(HandlerWithInvalidParameterException::class);
        new RouteHandlerCapsule(function ($zvm): ViewAbstract {
            return $zvm;
        });
    }

    public function testArgumentVariadic()
    {
        $this->expectException(HandlerWithInvalidParameterException::class);
        new RouteHandlerCapsule(function (ZZViewMock ...$zvm): ViewAbstract {
            return $zvm[0];
        });
    }

    public function testArgumentBuiltin()
    {
        $this->expectException(HandlerWithInvalidParameterException::class);
        new RouteHandlerCapsule(function (int ...$zvm): ViewAbstract {
            count($zvm);
            return new ZZViewMock;
        });
    }

    public function testNoReturnType()
    {
        $this->expectException(HandlerWithInvalidReturnTypeException::class);
        new RoutehandlerCapsule(function (ZZViewMock $zvm) {
            return $zvm;
        });
    }

    public function testInvalidReturnType()
    {
        $this->expectException(HandlerWithInvalidReturnTypeException::class);
        new RoutehandlerCapsule(function (ZZViewMock $zvm): self {
            return $this;
        });
    }
}