<?php

namespace Bellisq\Router\Intermediates;

use Bellisq\Router\Capsules\RouteHandlerCapsule;
use Bellisq\Router\Capsules\RouteRuleCapsule;
use Bellisq\Router\Containers\RoutePreconditionsContainer;
use Bellisq\Router\Containers\RoutesContainer;
use Bellisq\Router\RouteObject;
use Closure;
use Strict\Property\Intermediate\PropertyRegister;
use Strict\Property\Utility\StrictPropertyContainer;


/**
 * Class RouteHandlerRegister (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 *
 * @property-write Closure $handler
 */
class RouteHandlerRegister
    extends StrictPropertyContainer
{
    /** @var RoutePreconditionsContainer */
    protected $routePreconditionsContainer;

    /** @var RouteRuleCapsule */
    protected $routeRuleCapsule;

    /** @var RoutesContainer */
    protected $routesContainer;

    /** @var null|string */
    protected $name = null;

    /**
     * RouteHandlerRegister constructor.
     *
     * @param RoutesContainer             $routesContainer
     * @param RoutePreconditionsContainer $routePreconditionsContainer
     * @param RouteRuleCapsule            $routeRuleCapsule
     */
    public function __construct(RoutesContainer $routesContainer, RoutePreconditionsContainer $routePreconditionsContainer, RouteRuleCapsule $routeRuleCapsule)
    {
        parent::__construct();
        $this->routesContainer = $routesContainer;
        $this->routePreconditionsContainer = $routePreconditionsContainer;
        $this->routeRuleCapsule = $routeRuleCapsule;
    }

    /**
     * @param PropertyRegister $propertyRegister
     */
    protected function registerProperty(PropertyRegister $propertyRegister): void
    {
        $propertyRegister->newVirtualProperty('handler', null, function (Closure $value): void {
            $this->routesContainer->addRoute($this->name, new RouteObject(
                $this->routePreconditionsContainer,
                $this->routeRuleCapsule,
                new RouteHandlerCapsule($value)
            ));
        });
    }
}