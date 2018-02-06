<?php

namespace Bellisq\Router\Tests\TestCases\Capsules;

use Bellisq\Router\Capsules\RouteParameterDefinitionCapsule;
use Bellisq\Router\Exceptions\RouteParameterDefinition\InappropriateParameterNameException;
use Bellisq\Router\Exceptions\RouteParameterDefinition\InappropriateParameterTypeException;
use Bellisq\Router\Exceptions\RouteParameterDefinition\ParameterRangeViolationException;
use Bellisq\Router\RouteParameters;
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

    public function testTypeAppropriateness()
    {
        $this->assertTrue(RouteParameterDefinitionCapsule::isParamTypeAppropriate('?'));
        $this->assertTrue(RouteParameterDefinitionCapsule::isParamTypeAppropriate(':'));
        $this->assertFalse(RouteParameterDefinitionCapsule::isParamTypeAppropriate('/'));
    }

    public function testTypeAppropriateOrFail()
    {
        RouteParameterDefinitionCapsule::paramTypeAppropriateOrFail('?');

        $this->expectException(InappropriateParameterTypeException::class);
        RouteParameterDefinitionCapsule::paramTypeAppropriateOrFail('>');
    }

    public function testBehavior()
    {
        $t = new RouteParameterDefinitionCapsule(self::APPROPRIATE_PARAM_NAME, 'replacer', '?');

        $this->assertEquals(self::APPROPRIATE_PARAM_NAME, $t->getName());
        $this->assertEquals('replacer', $t->getReplacer());
        $this->assertEquals('?', $t->getType());

        $this->assertTrue(
            $t->isSatisfiedWith(new RouteParameters([self::APPROPRIATE_PARAM_NAME => 'あああ']))
        );
        $t->satisfiedOrFail(new RouteParameters([self::APPROPRIATE_PARAM_NAME => 'あああ']));

        $t = new RouteParameterDefinitionCapsule(self::APPROPRIATE_PARAM_NAME, 'replacer', ':');

        $this->assertTrue(
            $t->isSatisfiedWith(new RouteParameters([self::APPROPRIATE_PARAM_NAME => '12345']))
        );
        $this->assertFalse(
            $t->isSatisfiedWith(new RouteParameters([self::APPROPRIATE_PARAM_NAME => 'あああ']))
        );

        $this->expectException(ParameterRangeViolationException::class);
        $t->satisfiedOrFail(new RouteParameters([self::APPROPRIATE_PARAM_NAME => 'あああ']));
    }

    public function testConstructionFailByName()
    {
        $this->expectException(InappropriateParameterNameException::class);
        new RouteParameterDefinitionCapsule(self::INAPPROPRIATE_PARAM_NAME, 'replacer', '?');
    }

    public function testConstructionFailByType()
    {
        $this->expectException(InappropriateParameterTypeException::class);
        new RouteParameterDefinitionCapsule(self::APPROPRIATE_PARAM_NAME, 'replacer', '<');
    }
}