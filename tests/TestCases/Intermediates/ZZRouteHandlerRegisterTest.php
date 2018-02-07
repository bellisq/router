<?php

namespace Bellisq\Router\Tests\TestCases\Intermediates;

use Bellisq\MVC\ViewAbstract;
use Bellisq\Router\Capsules\RoutePreconditionCapsule;
use Bellisq\Router\Capsules\RouteRuleCapsule;
use Bellisq\Router\Containers\RoutePreconditionsContainer;
use Bellisq\Router\Containers\RoutesContainer;
use Bellisq\Router\Intermediates\RouteHandlerRegister;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock;
use PHPUnit\Framework\TestCase;


class ZZRouteHandlerRegisterTest
    extends TestCase
{
    public function testBehavior()
    {
        $rhr = new RouteHandlerRegister(
            $rc = new RoutesContainer,
            new RoutePreconditionsContainer(
                new RoutePreconditionCapsule
            ),
            new RouteRuleCapsule('/{:param1}')
        );

        $rhr->handler = function (): ViewAbstract {
            return new ZZViewMock;
        };

        $this->assertTrue(1 === $rc->count());
        $this->assertEquals('http://example.com:334/gg',
            $rc[0]->withScheme('http')->withHost('example.com')->withPort(334)->generateUri(['param1' => 'gg']));
    }
}