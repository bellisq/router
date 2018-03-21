<?php

namespace Bellisq\Router\Tests\TestCases\Intermediates;

use Bellisq\MVC\ViewAbstract;
use Bellisq\Router\Capsules\RoutePreconditionCapsule;
use Bellisq\Router\Capsules\RouteRuleCapsule;
use Bellisq\Router\Containers\RoutePreconditionsContainer;
use Bellisq\Router\Containers\RoutesContainer;
use Bellisq\Router\Exceptions\RouteConstraint\ConstraintViolationException;
use Bellisq\Router\Intermediates\RouteRegisterWithRule;
use Bellisq\Router\RouteParameters;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock;
use PHPUnit\Framework\TestCase;


class ZZRouteRegisterWithRuleTest
    extends TestCase
{
    /** @var RoutesContainer */
    private $rc;

    public function setUp()
    {
        $rrwr = new RouteRegisterWithRule(
            $this->rc = new RoutesContainer,
            new RoutePreconditionsContainer(
                new RoutePreconditionCapsule
            ),
            new RouteRuleCapsule('/{:param1}/{:param2}')
        );

        $rrwr
            ->withRegexConstraint('param1', '@^[a-zA-Z0-9]{3,100}$@u')
            ->withClosureConstraint(function (RouteParameters $params): bool {
                return $params->param2 === '334';
            })
            ->withName('test')
            ->handler = function (): ViewAbstract {
            return new ZZViewMock;
        };
    }

    public function testBehavior()
    {
        $this->assertTrue(isset($this->rc['test']));
    }

    public function testConstraintViolation1()
    {
        $this->expectException(ConstraintViolationException::class);
        $this->rc['test']
            ->withScheme('http')
            ->withHost('example.com')
            ->withPort('80')
            ->generateUri(['param1' => 'g', 'param2' => '334']);
    }

    public function testConstraintViolation2()
    {
        $this->expectException(ConstraintViolationException::class);
        $this->rc['test']
            ->withScheme('http')
            ->withHost('example.com')
            ->withPort('80')
            ->generateUri(['param1' => 'php', 'param2' => '335']);
    }
}