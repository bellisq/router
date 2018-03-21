<?php

namespace Bellisq\Router\Capsules;

use Bellisq\Router\Exceptions\RouteParameterDefinition\InappropriateParameterNameException;
use Bellisq\Router\Exceptions\RouteParameterDefinition\InappropriateParameterTypeException;
use Bellisq\Router\Exceptions\RouteParameterDefinition\ParameterRangeViolationException;
use Bellisq\Router\RouteParameters;


/**
 * [Class] Route Parameter Definition (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 *
 * @internal
 */
class RouteParameterDefinitionCapsule
{
    public const REGEX_PARAM_NAME       = '[a-zA-Z_][a-zA-Z0-9_]{0,63}';
    public const REGEX_PARAM_IDENTIFIER = '([a-zA-Z0-9\\._\\-]+)';
    public const REGEX_PARAM_GENERAL    = '(.*)';

    /**
     * Check whether or not the parameter name is appropriate.
     *
     * @param string $parameterName
     * @return bool
     */
    final public static function isParamNameAppropriate(string $parameterName): bool
    {
        return 1 === preg_match('@^' . self::REGEX_PARAM_NAME . '$@u', $parameterName);
    }

    /**
     * If the parameter name is inappropriate, throw an exception.
     *
     * @param string $parameterName
     *
     * @throws InappropriateParameterNameException
     */
    final public static function paramNameAppropriateOrFail(string $parameterName): void
    {
        if (!self::isParamNameAppropriate($parameterName)) {
            throw new InappropriateParameterNameException;
        }
    }

    public const TYPE_GENERAL    = '?';
    public const TYPE_IDENTIFIER = ':';

    private const TYPES = [self::TYPE_GENERAL => true, self::TYPE_IDENTIFIER => true];

    /**
     * Check whether or not the parameter type is appropriate.
     *
     * @param string $parameterType
     * @return bool
     */
    final public static function isParamTypeAppropriate(string $parameterType): bool
    {
        return isset(self::TYPES[$parameterType]);
    }

    /**
     * If the parameter type is inappropriate, throw an exception.
     *
     * @param string $parameterType
     *
     * @throws InappropriateParameterTypeException
     */
    final public static function paramTypeAppropriateOrFail(string $parameterType): void
    {
        if (!self::isParamTypeAppropriate($parameterType)) {
            throw new InappropriateParameterTypeException;
        }
    }

    /** @var string */
    private $name;

    /** @var string */
    private $replacer;

    /** @var string */
    private $type;

    /**
     * RouteParameterDefinitionCapsule constructor.
     *
     * @param string $name
     * @param string $replacer
     * @param string $type
     *
     * @throws InappropriateParameterNameException
     * @throws InappropriateParameterTypeException
     */
    public function __construct(string $name, string $replacer, string $type)
    {
        self::paramNameAppropriateOrFail($name);
        self::paramTypeAppropriateOrFail($type);

        $this->name = $name;
        $this->replacer = $replacer;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getReplacer(): string
    {
        return $this->replacer;

    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    private const REGEXES = [
        self::TYPE_IDENTIFIER => '@^' . self::REGEX_PARAM_IDENTIFIER . '$@u',
        self::TYPE_GENERAL    => '@^' . self::REGEX_PARAM_GENERAL . '$@u'
    ];

    /**
     * Check whether or not the parameter value is appropriate.
     *
     * @param RouteParameters $params
     * @return bool
     */
    public function isSatisfiedWith(RouteParameters $params): bool
    {
        return isset($params->{$this->getName()})
            && (1 === preg_match(self::REGEXES[$this->getType()], $params->{$this->getName()}));
    }

    /**
     * If the parameter value is inappropriate, throw an exception.
     *
     * @param RouteParameters $params
     *
     * @throws ParameterRangeViolationException
     */
    public function satisfiedOrFail(RouteParameters $params): void
    {
        if (!$this->isSatisfiedWith($params)) {
            throw new ParameterRangeViolationException;
        }
    }
}