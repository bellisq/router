<?php

namespace Bellisq\Router\Tests\TestCases\Capsules;

use Bellisq\Router\Capsules\RouteConstraintCapsule;
use Bellisq\Router\Exceptions\RouteConstraint\ConstraintViolationException;
use Bellisq\Router\Exceptions\RouteParameterDefinition\InappropriateParameterNameException;
use Bellisq\Router\RouteParameters;
use PHPUnit\Framework\TestCase;


class ZZRouteConstraintCapsuleTest
    extends TestCase
{
    public function testBehavior()
    {
        $pn = 'paramName';
        $rcc = new RouteConstraintCapsule($pn, '@^[a-z]{3}$@u');
        $this->assertTrue($rcc->isSatisfiedWith(new RouteParameters([$pn => 'php'])));
        $this->assertFalse($rcc->isSatisfiedWith(new RouteParameters([$pn => 'crud'])));

        $rcc->satisfiedOrFail(new RouteParameters([$pn => 'php']));

        $this->expectException(ConstraintViolationException::class);
        $rcc->satisfiedOrFail(new RouteParameters([$pn => 'crud']));
    }

    public function testInappropriateParamName()
    {
        $this->expectException(InappropriateParameterNameException::class);
        new RouteConstraintCapsule('0_a', '@^$@u');
    }
}