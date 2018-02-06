<?php

namespace Bellisq\Router\Tests\TestCases\Capsules;

use Bellisq\Router\Capsules\HostCapsule;
use Bellisq\Router\Capsules\MethodCapsule;
use Bellisq\Router\Capsules\PortCapsule;
use Bellisq\Router\Capsules\SchemeCapsule;
use DomainException;
use PHPUnit\Framework\TestCase;


class ZZCapsulesTest
    extends TestCase
{
    public function testPort()
    {
        $this->assertEquals(80, (new PortCapsule(80))->getPort());

        $this->expectException(DomainException::class);
        new PortCapsule(-1);
    }

    public function testMethod()
    {
        $this->assertEquals('GET', (new MethodCapsule('GET'))->getMethod());

        $this->expectException(DomainException::class);
        new MethodCapsule('HEAD');
    }

    public function testScheme()
    {
        $this->assertEquals('https', (new SchemeCapsule('HTTPS'))->getScheme());

        $this->expectException(DomainException::class);
        new SchemeCapsule('TCP');
    }

    public function testHost()
    {
        $this->assertEquals('example.org', (new HostCapsule('eXample.org'))->getHost());
    }
}