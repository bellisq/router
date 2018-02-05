<?php

namespace Bellisq\Router\Capsules;

use Bellisq\Router\Exceptions\RouteParameterDefinition\InappropriateParameterNameException;
use Bellisq\Router\Exceptions\RouteParameterDefinition\InappropriateParameterTypeException;


/**
 * [Class] Route Parameter Definition
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RouteParameterDefinitionCapsule
{
    public const REGEX_PARAM_NAME = '[a-zA-Z_][a-zA-Z0-9_]{0,63}';

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
}