<?php

namespace Bellisq\Router\Tests\TestCases;

use Bellisq\Request\Request;
use Bellisq\Request\RequestMutable;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock;
use Bellisq\Router\Tests\Mocks\Capsules\ZZViewMock2;
use Bellisq\Router\Tests\Mocks\ZZRouterMock;
use PHPUnit\Framework\TestCase;


class ZZStandardRouterTest
    extends TestCase
{
    /** @var ZZRouterMock */
    private $rm;

    public function setUp()
    {
        $this->rm = new ZZRouterMock;
    }

    public function testApi5()
    {
        $reqM = new RequestMutable([
            'REMOTE_ADDR' => '127.0.0.1',
            'REMOTE_PORT' => '334'
        ], [], [], [], []);

        $reqM->line->method = 'GET';
        $reqM->line->scheme = 'http';
        $reqM->line->host = 'example.com';
        $reqM->line->path = '/api/exist';

        $this->assertInstanceOf(ZZViewMock::class, ($this->rm->route(new Request($reqM))->getHandler())());
    }

    public function testApi7()
    {
        $reqM = new RequestMutable([
            'REMOTE_ADDR' => '127.0.0.1',
            'REMOTE_PORT' => '334'
        ], [], [], [], []);

        $reqM->line->method = 'GET';
        $reqM->line->scheme = 'http';
        $reqM->line->host = 'example.com';
        $reqM->line->path = '/api/existed';

        $this->assertInstanceOf(ZZViewMock2::class, ($this->rm->route(new Request($reqM))->getHandler())());
    }

    public function testNotFound()
    {
        $reqM = new RequestMutable([
            'REMOTE_ADDR' => '127.0.0.1',
            'REMOTE_PORT' => '334'
        ], [], [], [], []);

        $reqM->line->method = 'GET';
        $reqM->line->scheme = 'http';
        $reqM->line->host = 'example.com';
        $reqM->line->path = '/api/existing';

        $this->assertEquals('/api/existing', $this->rm->route(new Request($reqM))->getParameters()->path);
    }

    public function testAccessor()
    {
        $this->assertEquals('http://example.com/api/tttttre', $this->rm->getAccessor()
            ->get('api7')
            ->generateUri(['value' => 'tttttre']));
    }
}