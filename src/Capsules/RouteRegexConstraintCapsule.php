<?php

namespace Bellisq\Router\Capsules;

use Bellisq\Router\Capsules\RouteParameterDefinitionCapsule;
use Bellisq\Router\Exceptions\RouteConstraint\ConstraintViolationException;
use Bellisq\Router\RouteParameters;


/**
 * [Class] Route Regex Constraint Capsule (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 *
 * @internal
 */
class RouteRegexConstraintCapsule
    implements RouteConstraintCapsuleInterface
{
    /** @var string */
    private $paramName;

    /** @var string */
    private $regex;

    /**
     * RouteRegexConstraintCapsule constructor.
     *
     * @param string $paramName
     * @param string $regex
     */
    public function __construct(string $paramName, string $regex)
    {
        RouteParameterDefinitionCapsule::paramNameAppropriateOrFail($paramName);
        $this->paramName = $paramName;
        $this->regex = $regex;
    }

    /**
     * @param RouteParameters $params
     * @return bool
     */
    public function isSatisfiedWith(RouteParameters $params): bool
    {
        if (!isset($params->{$this->paramName})) {
            return false;
        }

        return 1 === preg_match($this->regex, $params->{$this->paramName});
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