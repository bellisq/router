<?php

namespace Bellisq\Router\Tests\TestCases\Capsules;

use Bellisq\Router\Capsules\RouteClosureConstraintCapsule;
use Bellisq\Router\Exceptions\RouteConstraint\ConstraintViolationException;
use Bellisq\Router\Exceptions\RouteConstraint\InvalidClosureException;
use Bellisq\Router\RouteParameters;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock;
use Bellisq\TypeMap\Utility\ObjectContainer;
use PHPUnit\Framework\TestCase;


class ZZRouteClosureConstraintCapsuleTest
    extends TestCase
{
    public function testBehavior()
    {
        $rcc = new RouteClosureConstraintCapsule(function (RouteParameters $params): bool {
            return (int)($params->id ?? -1) === 2;
        }, new ObjectContainer);

        $this->assertTrue($rcc->isSatisfiedWith(new RouteParameters(['id' => '2'])));
        $this->assertFalse($rcc->isSatisfiedWith(new RouteParameters(['id' => '3'])));
        $this->assertFalse($rcc->isSatisfiedWith(new RouteParameters([])));

        $rcc->satisfiedOrFail(new RouteParameters(['id' => '2']));

        $this->expectException(ConstraintViolationException::class);
        $rcc->satisfiedOrFail(new RouteParameters(['id' => '3']));
    }

    public function testInvalidClosure1()
    {
        $this->expectException(InvalidClosureException::class);
        new RouteClosureConstraintCapsule(function () { }, new ObjectContainer);
    }

    public function testInvalidClosure2()
    {
        $this->expectException(InvalidClosureException::class);
        new RouteClosureConstraintCapsule(function (): int { return 1; }, new ObjectContainer);
    }

    public function testInjection()
    {
        $rcc = new RouteClosureConstraintCapsule(function (RouteParameters $params, ZZViewMock $vm): bool {
            return $vm instanceof ZZViewMock;
        }, new ObjectContainer(new ZZViewMock));

        $this->assertTrue($rcc->isSatisfiedWith(new RouteParameters([])));
    }
}