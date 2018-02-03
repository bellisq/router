<?php

namespace Bellisq\Router\Tests\TestCases\Capsules;

use Bellisq\Router\Capsules\RouteParameterDefinitionCapsule;
use Bellisq\Router\Exceptions\RouteParameterDefinition\InappropriateParameterNameException;
use PHPUnit\Framework\TestCase;


class ZZRouteParameterDefinitionCapsuleTest
    extends TestCase
{
    private const APPROPRIATE_PARAM_NAME   = '_a9';
    private const INAPPROPRIATE_PARAM_NAME = '0aa';

    public function testAppropriateness()
    {
        $this->assertTrue(RouteParameterDefinitionCapsule::isParamNameAppropriate(self::APPROPRIATE_PARAM_NAME));
        $this->assertFalse(RouteParameterDefinitionCapsule::isParamNameAppropriate(self::INAPPROPRIATE_PARAM_NAME));
    }

    public function testAppropriateOrFail()
    {
        RouteParameterDefinitionCapsule::paramNameAppropriateOrFail(self::APPROPRIATE_PARAM_NAME);

        $this->expectException(InappropriateParameterNameException::class);
        RouteParameterDefinitionCapsule::paramNameAppropriateOrFail(self::INAPPROPRIATE_PARAM_NAME);
    }
}