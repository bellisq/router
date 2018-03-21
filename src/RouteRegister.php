<?php

namespace Bellisq\Router;

use Bellisq\Router\Capsules\RoutePreconditionCapsule;
use Bellisq\Router\Capsules\RouteRuleCapsule;
use Bellisq\Router\Containers\RoutePreconditionsContainer;
use Bellisq\Router\Containers\RoutesContainer;
use Bellisq\Router\Intermediates\RouteRegisterInitial;
use Bellisq\Router\Intermediates\RouteRegisterWithRule;
use Bellisq\TypeMap\TypeMapInterface;


/**
 * [Class] Route Register (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RouteRegister
    extends RouteRegisterInitial
    implements RoutableInterface
{
    private $typeMap;

    /**
     * RouteRegister constructor.
     *
     * @param RoutesContainer $container
     * @param TypeMapInterface|null $typeMap
     */
    public function __construct(RoutesContainer $container, ?TypeMapInterface $typeMap = null)
    {
        $this->typeMap = $typeMap;
        parent::__construct($container, $typeMap);
    }

    /**
     * @inheritdoc
     */
    public function route(string $rule): RouteRegisterWithRule
    {
        return new RouteRegisterWithRule(
            $this->container,
            new RoutePreconditionsContainer(new RoutePreconditionCapsule),
            new RouteRuleCapsule($rule, $this->typeMap)
        );
    }
}