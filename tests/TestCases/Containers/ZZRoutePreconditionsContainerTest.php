<?php

namespace Bellisq\Router\Tests\TestCases\Containers;

use Bellisq\Request\Request;
use Bellisq\Request\RequestMutable;
use Bellisq\Router\Capsules\HostCapsule;
use Bellisq\Router\Capsules\PortCapsule;
use Bellisq\Router\Capsules\RoutePreconditionCapsule;
use Bellisq\Router\Capsules\SchemeCapsule;
use Bellisq\Router\Containers\RoutePreconditionsContainer;
use Bellisq\Router\Exceptions\RoutePreconditionsContainer\MultiplePreconditionException;
use PHPUnit\Framework\TestCase;


class ZZRoutePreconditionsContainerTest
    extends TestCase
{
    public function testBehavior()
    {
        $v1 = (new RoutePreconditionCapsule())->withSchemes(new SchemeCapsule('HTTP'))->withHosts(new HostCapsule('example.org'));

        $v2 = $v1->withPorts(new PortCapsule(80));
        $rpc = new RoutePreconditionsContainer($v2);
        $this->assertEquals($v2->generateUri(), $rpc->generateUri());

        $rpc = new RoutePreconditionsContainer(
            $v1,
            (new RoutePreconditionCapsule())->withSchemes(new SchemeCapsule('HTTPS'))->withHosts(new HostCapsule('example.jp'))
        );
        $reqM = new RequestMutable([
            'REMOTE_ADDR' => '127.0.0.1',
            'REMOTE_PORT' => '334'
        ], [], [], [], []);

        $reqM->line->scheme = 'http';
        $reqM->line->host = 'example.org';
        $this->assertTrue($rpc->isSatisfiedWith(new Request($reqM)));

        $reqM->line->scheme = 'https';
        $this->assertFalse($rpc->isSatisfiedWith(new Request($reqM)));

        $reqM->line->host = 'example.jp';
        $this->assertTrue($rpc->isSatisfiedWith(new Request($reqM)));

        $this->expectException(MultiplePreconditionException::class);
        $rpc->generateUri();
    }

    public function testWithRestriction()
    {
        $rpc = new RoutePreconditionsContainer(
            (new RoutePreconditionCapsule())->withSchemes(new SchemeCapsule('HTTP'))->withHosts(new HostCapsule('example.org')),
            (new RoutePreconditionCapsule())->withSchemes(new SchemeCapsule('HTTPS'))->withHosts(new HostCapsule('example.jp'))
        );

        $reqM = new RequestMutable([
            'REMOTE_ADDR' => '127.0.0.1',
            'REMOTE_PORT' => '334'
        ], [], [], [], []);

        $reqM->line->scheme = 'https';
        $reqM->line->host = 'example.jp';

        $reqM->line->port = 334;
        $this->assertTrue($rpc->isSatisfiedWith(new Request($reqM)));

        $this->assertFalse($rpc->withPort(new PortCapsule(80))
            ->isSatisfiedWith(new Request($reqM)));

        $reqM->line->port = 80;
        $this->assertTrue($rpc->withPort(new PortCapsule(80))
            ->isSatisfiedWith(new Request($reqM)));

        $this->assertTrue($rpc
            ->isSatisfiedWith(new Request($reqM)));

        $this->assertFalse($rpc->withScheme(new SchemeCapsule('http'))
            ->isSatisfiedWith(new Request($reqM)));
    }
}