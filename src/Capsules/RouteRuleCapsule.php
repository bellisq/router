<?php

namespace Bellisq\Router\Capsules;

use Bellisq\Router\Exceptions\RouteRuleCapsule\DuplicateParameterNameException;
use Bellisq\Router\Exceptions\RouteRuleCapsule\InvalidConstraintException;
use Bellisq\Router\Capsules\RouteRegexConstraintCapsule;
use Bellisq\Router\RouteParameters;


/**
 * [Class] RouteRuleCapsule (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 *
 * @internal
 */
class RouteRuleCapsule
{
    /** @var RouteParameterDefinitionCapsule[] */
    private $parameterDefinitions = [];

    /** @var string */
    private $stdRule;

    /** @var string */
    private $regex;

    /** @var RouteConstraintCapsuleInterface[] */
    private $constraints = [];

    /**
     * RouteRuleCapsule constructor.
     *
     * @param string $rule
     */
    public function __construct(string $rule)
    {
        $rule = '/' . trim($rule, '/');
        preg_match_all(
            '@\\{(' .
            preg_quote(RouteParameterDefinitionCapsule::TYPE_IDENTIFIER, '@')
            . '|' .
            preg_quote(RouteParameterDefinitionCapsule::TYPE_GENERAL, '@')
            . ')(' .
            RouteParameterDefinitionCapsule::REGEX_PARAM_NAME . ')\\}@u', $rule, $m);

        /** @var string[] $rawParams */
        $rawParams = $m[0];     /* ['{:param1}', '{?param2}', ...] */

        /** @var string[] $paramTypes */
        $paramTypes = $m[1];    /* [':', ' ? ', ...] */

        /** @var string[] $paramNames */
        $paramNames = $m[2];    /* ['param1', 'param2', ...] */

        /** @var string[] $replacers */
        $replacers = [];        /* ['RouteParameter0ByBellisqRouter', 'RouteParameter1ByBellisqRouter', ...] */

        /** @var string[] $regexes */
        $regexes = [];          /* [REGEX_PARAM_***, REGEX_PARAM_***, ...] */

        foreach ($paramNames as $paramNo => $paramName) {
            if (isset($this->parameterDefinitions[$paramName])) {
                throw new DuplicateParameterNameException;
            }

            $replacer = "RouteParameter{$paramNo}ByBellisqRouter";
            $paramType = $paramTypes[$paramNo];
            if ($paramType === RouteParameterDefinitionCapsule::TYPE_IDENTIFIER) {
                $partialRegex = RouteParameterDefinitionCapsule::REGEX_PARAM_IDENTIFIER;
            } else {
                assert($paramType === RouteParameterDefinitionCapsule::TYPE_GENERAL);
                $partialRegex = RouteParameterDefinitionCapsule::REGEX_PARAM_GENERAL;
            }

            $replacers[$paramNo] = $replacer;
            $regexes[$paramNo] = $partialRegex;
            $this->parameterDefinitions[$paramName] = new RouteParameterDefinitionCapsule($paramName, $replacer, $paramType);
        }

        $regex = str_replace(
            $replacers, $regexes,
            preg_quote($raw = str_replace($rawParams, $replacers, $rule), '@')
        );

        $this->stdRule = $raw;
        $this->regex = "@^{$regex}$@u";
    }

    /**
     * @param string $paramName
     * @param string $regex
     * @return RouteRuleCapsule
     *
     * @throws InvalidConstraintException
     */
    public function withRegexConstraint(string $paramName, string $regex): self
    {
        if (!isset($this->parameterDefinitions[$paramName])) {
            throw new InvalidConstraintException;
        }

        $ret = clone $this;
        $ret->constraints[] = new RouteRegexConstraintCapsule($paramName, $regex);
        return $ret;
    }

    /**
     * @param string $path
     * @return RouteParameters|null
     */
    public function match(string $path): ?RouteParameters
    {
        $path = '/' . trim($path, '/');
        if (1 !== preg_match($this->regex, $path, $m)) {
            return null;
        }

        $paramArray = [];
        $index = 1;
        foreach ($this->parameterDefinitions as $parameterDefinition) {
            $paramArray[$parameterDefinition->getName()] = $m[$index++];
        }

        $params = new RouteParameters($paramArray);
        foreach ($this->constraints as $constraint) {
            if (!$constraint->isSatisfiedWith($params)) {
                return null;
            }
        }

        return $params;
    }

    /**
     * @param RouteParameters $params
     * @return string
     */
    public function generatePath(RouteParameters $params): string
    {
        $replacers = [];
        $values = [];
        foreach ($this->parameterDefinitions as $parameterDefinition) {
            $parameterDefinition->satisfiedOrFail($params);
            $replacers[] = $parameterDefinition->getReplacer();
            $values[] = $params->{$parameterDefinition->getName()};
        }

        foreach ($this->constraints as $constraint) {
            $constraint->satisfiedOrFail($params);
        }

        return str_replace($replacers, $values, $this->stdRule);
    }
}