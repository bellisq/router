<?php

namespace Bellisq\Router\Capsules;

use Bellisq\Router\Exceptions\RouteConstraint\ConstraintViolationException;
use Bellisq\Router\Exceptions\RouteConstraint\InvalidClosureException;
use Bellisq\Router\RouteParameters;
use Bellisq\TypeMap\TypeMapInterface;
use Bellisq\TypeMap\Utility\ArgumentAutoComplete;
use Bellisq\TypeMap\Utility\ObjectContainer;
use Bellisq\TypeMap\Utility\TypeMapAggregate;
use Closure;
use ReflectionException;
use ReflectionFunction;


/**
 * [Class] Route Closure Constraint Capsule (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.4.0
 *
 * @internal
 */
class RouteClosureConstraintCapsule
    implements RouteConstraintCapsuleInterface
{
    /** @var Closure */
    private $closure;

    /** @var TypeMapInterface */
    private $typeMap;

    /**
     * RouteClosureConstraintCapsule constructor.
     *
     * @param Closure $closure return type must be bool
     * @param TypeMapInterface $typeMap
     *
     * @throws InvalidClosureException
     */
    public function __construct(Closure $closure, TypeMapInterface $typeMap)
    {
        try {
            if (
                is_null($type = (new ReflectionFunction($closure))->getReturnType())
                ||
                !$type->isBuiltin()
                ||
                $type->getName() !== 'bool'
            ) throw new InvalidClosureException;
        } catch (ReflectionException $exception) {
            throw new InvalidClosureException('', 0, $exception);
        }

        $this->closure = $closure;
        $this->typeMap = $typeMap;
    }

    /**
     * @param RouteParameters $params
     * @return bool
     */
    public function isSatisfiedWith(RouteParameters $params): bool
    {
        $ac = new ArgumentAutoComplete(new TypeMapAggregate($this->typeMap, new ObjectContainer($params)));
        return $ac->call($this->closure);
    }

    /**
     * @param RouteParameters $params
     *
     * @throws ConstraintViolationException
     */
    public function satisfiedOrFail(RouteParameters $params): void
    {
        if (!$this->isSatisfiedWith($params)) {
            throw new ConstraintViolationException;
        }
    }
}