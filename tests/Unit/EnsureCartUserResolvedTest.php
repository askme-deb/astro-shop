<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Middleware\EnsureCartUserResolved;
use App\Services\Api\CartUserResolverService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use Symfony\Component\HttpFoundation\Cookie;

class EnsureCartUserResolvedTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_attaches_cookie_to_response_if_service_indicates_so()
    {
        // Mock Resolver
        $mockResolver = Mockery::mock(CartUserResolverService::class);
        $mockResolver->shouldReceive('resolve')->andReturn([
            'type' => 'guest',
            'id' => 'guest_123',
            'set_cookie' => true,
        ]);
        
        $cookie = new Cookie('guest_user_id', 'guest_123');
        $mockResolver->shouldReceive('makeGuestCookie')->with('guest_123')->andReturn($cookie);
        
        $middleware = new EnsureCartUserResolved($mockResolver);
        
        $request = Request::create('/', 'GET');
        
        $response = $middleware->handle($request, function ($req) {
            return new Response('content');
        });
        
        // Assert cookie is attached
        $cookies = $response->headers->getCookies();
        $this->assertCount(1, $cookies);
        $this->assertEquals('guest_user_id', $cookies[0]->getName());
        $this->assertEquals('guest_123', $cookies[0]->getValue());
    }

    /** @test */
    public function it_does_not_attach_cookie_if_not_needed()
    {
        // Mock Resolver
        $mockResolver = Mockery::mock(CartUserResolverService::class);
        $mockResolver->shouldReceive('resolve')->andReturn([
            'type' => 'guest',
            'id' => 'guest_123',
            'set_cookie' => false, // Existing cookie valid
        ]);
        
        $middleware = new EnsureCartUserResolved($mockResolver);
        
        $request = Request::create('/', 'GET');
        
        $response = $middleware->handle($request, function ($req) {
            return new Response('content');
        });
        
        // Assert no cookie attached
        $cookies = $response->headers->getCookies();
        $this->assertCount(0, $cookies);
    }
}
