<?php

namespace Bellisq\Router\Tests\TestCases\Capsules;

use Bellisq\Router\Capsules\RouteParameterDefinition;
use Bellisq\Router\Exceptions\RouteParameterDefinition\InappropriateParameterNameException;
use PHPUnit\Framework\TestCase;


class ZZRouteParameterDefinitionTest
    extends TestCase
{
    private const APPROPRIATE_PARAM_NAME   = '_a9';
    private const INAPPROPRIATE_PARAM_NAME = '0aa';

    public function testAppropriateness()
    {
        $this->assertTrue(RouteParameterDefinition::isParamNameAppropriate(self::APPROPRIATE_PARAM_NAME));
        $this->assertFalse(RouteParameterDefinition::isParamNameAppropriate(self::INAPPROPRIATE_PARAM_NAME));
    }

    public function testAppropriateOrFail()
    {
        RouteParameterDefinition::paramNameAppropriateOrFail(self::APPROPRIATE_PARAM_NAME);

        $this->expectException(InappropriateParameterNameException::class);
        RouteParameterDefinition::paramNameAppropriateOrFail(self::INAPPROPRIATE_PARAM_NAME);
    }
}