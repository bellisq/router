<?php

namespace Bellisq\Router\Tests\TestCases;

use Bellisq\Router\Exceptions\RouteParameters\InvalidKeyException;
use Bellisq\Router\Exceptions\RouteParameters\NonStringValueException;
use Bellisq\Router\RouteParameters;
use PHPUnit\Framework\TestCase;
use stdClass;
use Strict\Property\Errors\DisabledPropertyInjectionError;
use Strict\Property\Errors\IndeliblePropertyError;
use Strict\Property\Errors\ReadonlyPropertyError;
use Strict\Property\Errors\UndefinedPropertyError;


class ZZRouteParameterTest
    extends TestCase
{
    /** @var RouteParameters */
    private $params;

    public function setUp()
    {
        $this->params = new RouteParameters([
            'givenName'  => 'John',
            'familyName' => new class
            {
                public function __toString(): string
                {
                    return 'Smith';
                }
            }
        ]);
    }

    public function testBehavior()
    {
        $this->assertTrue(isset($this->params->givenName));
        $this->assertTrue(isset($this->params->familyName));
        $this->assertFalse(isset($this->params->undefinedParameter));

        $this->assertEquals('John', $this->params->givenName);
        $this->assertEquals('Smith', $this->params->familyName);

        $this->assertTrue(is_string($this->params->givenName));
        $this->assertTrue(is_string($this->params->familyName));
    }

    public function testInvalidKey()
    {
        $this->expectException(InvalidKeyException::class);
        new RouteParameters(['3']);
    }

    public function testNonScalarValue()
    {
        $this->expectException(NonStringValueException::class);
        new RouteParameters(['givenNames' => ['John', 'Michel']]);
    }

    public function testNotStringConvertibleValue()
    {
        $this->expectException(NonStringValueException::class);
        new RouteParameters(['givenName' => new stdClass]);
    }

    public function testUndefinedGet()
    {
        $this->expectException(UndefinedPropertyError::class);
        $this->params->undefinedParameter;
    }

    public function testDefinedUnset()
    {
        $this->expectException(IndeliblePropertyError::class);
        unset($this->params->givenName);
    }

    public function testUndefinedUnset()
    {
        $this->expectException(UndefinedPropertyError::class);
        unset($this->params->undefinedParameter);
    }

    public function testDefinedSet()
    {
        $this->expectException(ReadonlyPropertyError::class);
        $this->params->givenName = 'Michel';
    }

    public function testUndefinedSet()
    {
        $this->expectException(DisabledPropertyInjectionError::class);
        $this->params->undefinedParameter = 'Greedy';
    }
}