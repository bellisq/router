<?php

namespace Bellisq\Router\Exceptions\RouteParameterDefinition;

use LogicException;


/**
 * [Exception] Parameter Range Violation
 *
 * Parameter-type (':' or '?') restricts the range of the parameter.
 * If the range is violated, this exception will be thrown.
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/router
 * @since 1.0.0
 */
class ParameterRangeViolationException
    extends LogicException
{

}