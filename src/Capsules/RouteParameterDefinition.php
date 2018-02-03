<?php

namespace Bellisq\Router\Capsules;

use Bellisq\Router\Exceptions\RouteParameterDefinition\InappropriateParameterNameException;


/**
 * [Class] Route Parameter Definition
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class RouteParameterDefinition
{
    public const PARAMETER_NAME_REGEX = '[a-zA-Z_][a-zA-Z0-9_]{0,63}';

    /**
     * Check whether or not the parameter name is appropriate.
     *
     * @param string $parameterName
     * @return bool
     */
    public static function isParamNameAppropriate(string $parameterName): bool
    {
        return 1 === preg_match('@^' . self::PARAMETER_NAME_REGEX . '$@u', $parameterName);
    }

    /**
     * If the parameter name is inappropriate, throw an exception.
     *
     * @param string $parameterName
     *
     * @throws InappropriateParameterNameException
     */
    public static function paramNameAppropriateOrFail(string $parameterName): void
    {
        if (!self::isParamNameAppropriate($parameterName)) {
            throw new InappropriateParameterNameException;
        }
    }
}