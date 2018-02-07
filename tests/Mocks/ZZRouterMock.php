<?php

namespace Bellisq\Router\Tests\Mocks;

use Bellisq\MVC\ViewAbstract;
use Bellisq\Router\RouteRegister;
use Bellisq\Router\StandardRouter;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock2;


class ZZRouterMock
    extends StandardRouter
{
    /**
     * @inheritdoc
     */
    protected static function registerRoutes(RouteRegister $routeRegister): void
    {
        $routeRegister
            ->forMethod('GET')->forScheme('http')->forHost('example.com')->forPort(80)
            ->route('/api/{:value}')
            ->withConstraint('value', '@^[a-z]{5}$@u')
            ->withName('api5')
            ->handler = function (): ViewAbstract {
            return new ZZViewMock;
        };

        $routeRegister
            ->forMethod('GET')->forScheme('http')->forHost('example.com')->forPort(80)
            ->route('/api/{:value}')
            ->withConstraint('value', '@^[a-z]{7}$@u')
            ->withName('api7')
            ->handler = function (): ViewAbstract {
            return new ZZViewMock2;
        };
    }
}