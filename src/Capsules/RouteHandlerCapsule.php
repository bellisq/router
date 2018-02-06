<?php

namespace Bellisq\Router\Capsules;

use Bellisq\MVC\ViewAbstract;
use Bellisq\Router\Exceptions\RouteHandlerCapsule\HandlerWithInvalidParameterException;
use Bellisq\Router\Exceptions\RouteHandlerCapsule\HandlerWithInvalidReturnTypeException;
use Closure;
use ReflectionFunction;


/**
 * [Class] Route Handler Capsule (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RouteHandlerCapsule
{
    /** @var Closure */
    private $handler;

    /**
     * RouteHandlerCapsule constructor.
     *
     * @param Closure $handler
     *
     * @throws HandlerWithInvalidParameterException
     * @throws HandlerWithInvalidReturnTypeException
     */
    public function __construct(Closure $handler)
    {
        $refFunc = new ReflectionFunction($handler);
        if (!$refFunc->hasReturnType() || $refFunc->getReturnType()->getName() !== ViewAbstract::class) {
            throw new HandlerWithInvalidReturnTypeException;
        }

        $refParams = $refFunc->getParameters();
        foreach ($refParams as $refParam) {
            if (!$refParam->hasType() || $refParam->isVariadic() || $refParam->getType()->isBuiltin()) {
                throw new HandlerWithInvalidParameterException;
            }
        }

        $this->handler = $handler;
    }

    /**
     * @return Closure
     */
    public function getHandler(): Closure
    {
        return $this->handler;
    }
}