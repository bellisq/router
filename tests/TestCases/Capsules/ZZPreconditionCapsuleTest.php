<?php

namespace Bellisq\Router\Tests\TestCases\Capsules;

use Bellisq\Request\Request;
use Bellisq\Request\RequestMutable;
use Bellisq\Router\Capsules\HostCapsule;
use Bellisq\Router\Capsules\MethodCapsule;
use Bellisq\Router\Capsules\PortCapsule;
use Bellisq\Router\Capsules\PreconditionCapsule;
use Bellisq\Router\Capsules\SchemeCapsule;
use Bellisq\Router\Exceptions\PreconditionCapsule\MultipleCandidateException;
use PHPUnit\Framework\TestCase;


class ZZPreconditionCapsuleTest
    extends TestCase
{
    /** @var RequestMutable */
    private $reqM;

    public function setUp()
    {
        $this->reqM = new RequestMutable([
            'REMOTE_ADDR' => '127.0.0.1',
            'REMOTE_PORT' => '334'
        ], [], [], [], []);
    }

    public function testBehavior()
    {
        $px = new PreconditionCapsule;

        $pc = $px;
        $this->assertTrue($pc->isSatisfiedWith(new Request($this->reqM)));


        $this->reqM->line->port = 334;

        $pc = $px->restrictPort(new PortCapsule(334));
        $this->assertTrue($pc->isSatisfiedWith(new Request($this->reqM)));

        $pc = $px->withPorts(new PortCapsule(334), new PortCapsule(443));
        $this->assertTrue($pc->isSatisfiedWith(new Request($this->reqM)));
        $pc = $pc->restrictPort(new PortCapsule(443));
        $this->assertFalse($pc->isSatisfiedWith(new Request($this->reqM)));

        $pc = $px->withPorts(new PortCapsule(80), new PortCapsule(8080));
        $this->assertFalse($pc->isSatisfiedWith(new Request($this->reqM)));
        $pc = $pc->restrictPort(new PortCapsule(80));
        $this->assertFalse($pc->isSatisfiedWith(new Request($this->reqM)));


        $this->reqM->line->method = Request::METHOD_POST;

        $pc = $px->restrictMethod(new MethodCapsule('POST'));
        $this->assertTrue($pc->isSatisfiedWith(new Request($this->reqM)));

        $pc = $px->withMethods(new MethodCapsule('POST'), new MethodCapsule('PUT'));
        $this->assertTrue($pc->isSatisfiedWith(new Request($this->reqM)));
        $pc = $pc->restrictMethod(new MethodCapsule('PUT'));
        $this->assertFalse($pc->isSatisfiedWith(new Request($this->reqM)));

        $pc = $px->withMethods(new MethodCapsule('GET'), new MethodCapsule('PUT'));
        $this->assertFalse($pc->isSatisfiedWith(new Request($this->reqM)));
        $pc = $pc->restrictMethod(new MethodCapsule('GET'));
        $this->assertFalse($pc->isSatisfiedWith(new Request($this->reqM)));


        $this->reqM->line->scheme = 'HTTPS';

        $pc = $px->restrictScheme(new SchemeCapsule('HTTPS'));
        $this->assertTrue($pc->isSatisfiedWith(new Request($this->reqM)));

        $pc = $px->withSchemes(new SchemeCapsule('HTTPS'), new SchemeCapsule('HTTP'));
        $this->assertTrue($pc->isSatisfiedWith(new Request($this->reqM)));
        $pc = $pc->restrictScheme(new SchemeCapsule('HTTP'));
        $this->assertFalse($pc->isSatisfiedWith(new Request($this->reqM)));

        $pc = $px->withSchemes(new SchemeCapsule('HTTP'));
        $this->assertFalse($pc->isSatisfiedWith(new Request($this->reqM)));


        $this->reqM->line->host = 'example.org';
        $pc = $px->withHosts(new HostCapsule('example.org'));
        $this->assertTrue($pc->isSatisfiedWith(new Request($this->reqM)));
        $pc = $px->withHosts(new HostCapsule('example.com'));
        $this->assertFalse($pc->isSatisfiedWith(new Request($this->reqM)));

        $this->assertNull($px->withSchemes(new SchemeCapsule('HTTP'))->restrictScheme(new SchemeCapsule('HTTPS')));


        $pc = $px
            ->withHosts(new HostCapsule('example.org'))
            ->withSchemes(new SchemeCapsule('https'))
            ->withPorts(new PortCapsule(334));
        $this->assertEquals('https://example.org:334', $pc->generateUri());

        $pc = $pc
            ->withPorts(new PortCapsule(443));
        $this->assertEquals('https://example.org', $pc->generateUri());
    }

    public function testMCPort()
    {
        $pc = (new PreconditionCapsule)->withPorts(new PortCapsule(79), new PortCapsule(80));

        $this->expectException(MultipleCandidateException::class);
        $pc->generateUri();
    }

    public function testMCScheme()
    {
        $pc = (new PreconditionCapsule)->withSchemes(new SchemeCapsule('HTTP'), new SchemeCapsule('HTTPS'));

        $this->expectException(MultipleCandidateException::class);
        $pc->generateUri();
    }

    public function testMCHost()
    {
        $pc = (new PreconditionCapsule)->withHosts(new HostCapsule('ff.com'), new HostCapsule('gg.com'));

        $this->expectException(MultipleCandidateException::class);
        $pc->generateUri();
    }
}