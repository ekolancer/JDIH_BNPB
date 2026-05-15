<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Middleware\RestrictIpMiddleware;
use Illuminate\Http\Request;

class RestrictIpMiddlewareTest extends TestCase
{
    private RestrictIpMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new RestrictIpMiddleware();
    }

    /** @test */
    public function ipv4_single_ip_matches(): void
    {
        $method = new \ReflectionMethod($this->middleware, 'ipInRange');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($this->middleware, '192.168.1.100', '192.168.1.100'));
        $this->assertFalse($method->invoke($this->middleware, '192.168.1.101', '192.168.1.100'));
    }

    /** @test */
    public function ipv4_cidr_matches(): void
    {
        $method = new \ReflectionMethod($this->middleware, 'ipInRange');
        $method->setAccessible(true);

        // 10.220.52.0/22 covers 10.220.52.0 - 10.220.55.255
        $this->assertTrue($method->invoke($this->middleware, '10.220.52.1', '10.220.52.0/22'));
        $this->assertTrue($method->invoke($this->middleware, '10.220.55.254', '10.220.52.0/22'));
        $this->assertFalse($method->invoke($this->middleware, '10.220.56.1', '10.220.52.0/22'));

        // 192.168.1.0/24
        $this->assertTrue($method->invoke($this->middleware, '192.168.1.50', '192.168.1.0/24'));
        $this->assertFalse($method->invoke($this->middleware, '192.168.2.1', '192.168.1.0/24'));
    }

    /** @test */
    public function ipv6_single_ip_matches(): void
    {
        $method = new \ReflectionMethod($this->middleware, 'ipInRange');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($this->middleware, '2001:db8::1', '2001:db8::1'));
        $this->assertFalse($method->invoke($this->middleware, '2001:db8::2', '2001:db8::1'));
    }

    /** @test */
    public function ipv6_cidr_matches(): void
    {
        $method = new \ReflectionMethod($this->middleware, 'ipInRange');
        $method->setAccessible(true);

        // 2001:db8::/32 covers all 2001:db8:*
        $this->assertTrue($method->invoke($this->middleware, '2001:db8::1', '2001:db8::/32'));
        $this->assertTrue($method->invoke($this->middleware, '2001:db8:abcd::1', '2001:db8::/32'));
        $this->assertFalse($method->invoke($this->middleware, '2001:db9::1', '2001:db8::/32'));
    }

    /** @test */
    public function ipv4_does_not_match_ipv6_range(): void
    {
        $method = new \ReflectionMethod($this->middleware, 'ipInRange');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke($this->middleware, '192.168.1.1', '2001:db8::/32'));
    }

    /** @test */
    public function ipv6_does_not_match_ipv4_range(): void
    {
        $method = new \ReflectionMethod($this->middleware, 'ipInRange');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke($this->middleware, '2001:db8::1', '192.168.1.0/24'));
    }

    /** @test */
    public function invalid_ip_returns_false(): void
    {
        $method = new \ReflectionMethod($this->middleware, 'ipInRange');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke($this->middleware, 'not-an-ip', '192.168.1.0/24'));
        $this->assertFalse($method->invoke($this->middleware, '192.168.1.1', 'invalid/24'));
    }
}
