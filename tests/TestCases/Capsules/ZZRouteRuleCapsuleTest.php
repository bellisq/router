<?php

namespace Bellisq\Router\Tests\TestCases\Capsules;

use Bellisq\Router\Capsules\RouteRuleCapsule;
use Bellisq\Router\Exceptions\RouteConstraint\ConstraintViolationException;
use Bellisq\Router\Exceptions\RouteParameterDefinition\ParameterRangeViolationException;
use Bellisq\Router\Exceptions\RouteRuleCapsule\DuplicateParameterNameException;
use Bellisq\Router\Exceptions\RouteRuleCapsule\InvalidConstraintException;
use Bellisq\Router\RouteParameters;
use PHPUnit\Framework\TestCase;


class ZZRouteRuleCapsuleTest
    extends TestCase
{
    /** @var RouteRuleCapsule */
    private $routeRule;

    public function setUp()
    {
        $this->routeRule = (new RouteRuleCapsule('/{:username}/{:month}/{?type}'))
            ->withRegexConstraint('username', '@^[a-zA-Z_][a-zA-Z0-9_]{4,14}$@u')
            ->withRegexConstraint('month', '@^(10|11|12|[1-9]{1})$@');
    }

    public function testInvalidConstraint()
    {
        $this->expectException(InvalidConstraintException::class);
        $this->routeRule->withRegexConstraint('unregisteredParamName', '');
    }

    public function testMatch()
    {
        $rParam = $this->routeRule->match('/U_334/12/今月のデータ');

        $this->assertNotNull($rParam);
        $this->assertEquals('U_334', $rParam->username);
        $this->assertEquals('12', $rParam->month);
        $this->assertEquals('今月のデータ', $rParam->type);

        $rParam = $this->routeRule->match('/U_334/12/今月のデータ/');

        $this->assertNotNull($rParam);
        $this->assertEquals('U_334', $rParam->username);
        $this->assertEquals('12', $rParam->month);
        $this->assertEquals('今月のデータ', $rParam->type);

        $rParam = $this->routeRule->match('U_334/12/今月のデータ');

        $this->assertNotNull($rParam);
        $this->assertEquals('U_334', $rParam->username);
        $this->assertEquals('12', $rParam->month);
        $this->assertEquals('今月のデータ', $rParam->type);

        $this->assertNull($this->routeRule->match('/あああ/12/3'));
        $this->assertNull($this->routeRule->match('/あああ/f/12/3'));
        $this->assertNull($this->routeRule->match('/u1/12/3'));
    }

    public function testGenerate()
    {
        $this->assertEquals('/U_334/12/今月のデータ', $this->routeRule->generatePath(
            new RouteParameters([
                'username' => 'U_334',
                'month'    => '12',
                'type'     => '今月のデータ'
            ])
        ));

        $this->assertEquals('/', (new RouteRuleCapsule(''))->generatePath(new RouteParameters([])));
    }

    public function testGenerateFailByGeneralValidation()
    {
        $this->expectException(ParameterRangeViolationException::class);
        $this->routeRule->generatePath(
            new RouteParameters([
                'username' => 'あああ',
                'month'    => '12',
                'type'     => '今月のデータ'
            ])
        );
    }

    public function testGenerateFailByConstraintValidation()
    {
        $this->expectException(ConstraintViolationException::class);
        $this->routeRule->generatePath(
            new RouteParameters([
                'username' => 'U334',
                'month'    => '12',
                'type'     => '今月のデータ'
            ])
        );
    }

    public function testDuplicateParameter()
    {
        $this->expectException(DuplicateParameterNameException::class);
        new RouteRuleCapsule('/{:wcd}/{?wcd}');
    }
}