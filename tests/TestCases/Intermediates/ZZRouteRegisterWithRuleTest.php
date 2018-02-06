<?php

namespace Bellisq\Router\Tests\TestCases\Intermediates;

use Bellisq\MVC\ViewAbstract;
use Bellisq\Router\Capsules\PreconditionCapsule;
use Bellisq\Router\Capsules\RouteRuleCapsule;
use Bellisq\Router\Containers\RoutePreconditionsContainer;
use Bellisq\Router\Containers\RoutesContainer;
use Bellisq\Router\Exceptions\RouteConstraint\ConstraintViolationException;
use Bellisq\Router\Intermediates\RouteRegisterWithRule;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock;
use PHPUnit\Framework\TestCase;


class ZZRouteRegisterWithRuleTest
    extends TestCase
{
    public function testBehavior()
    {
        $rrwr = new RouteRegisterWithRule(
            $rc = new RoutesContainer,
            new RoutePreconditionsContainer(
                new PreconditionCapsule
            ),
            new RouteRuleCapsule('/{:param1}')
        );

        $rrwr
            ->withConstraint('param1', '@^[a-zA-Z0-9]{3,100}$@u')
            ->withName('test')
            ->handler = function (): ViewAbstract {
            return new ZZViewMock;
        };

        $this->assertTrue(isset($rc['test']));

        $this->expectException(ConstraintViolationException::class);
        $rc['test']
            ->withScheme('http')
            ->withHost('example.com')
            ->withPort('80')
            ->generateUri(['param1' => 'g']);
    }
}