<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Api\CartUserResolverService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CartUserResolverTest extends TestCase
{
    /** @test */
    public function it_resolves_guest_id_from_cookie()
    {
        $service = new CartUserResolverService();
        $uuid = 'guest_' . Str::uuid();
        
        $request = Request::create('/', 'GET');
        $request->cookies->set('guest_user_id', $uuid);
        
        $resolved = $service->resolve($request);
        
        $this->assertEquals('guest', $resolved['type']);
        $this->assertEquals($uuid, $resolved['id']);
        $this->assertFalse($resolved['set_cookie']);
    }

    /** @test */
    public function it_generates_new_guest_id_if_cookie_missing()
    {
        $service = new CartUserResolverService();
        
        $request = Request::create('/', 'GET');
        // No cookie
        
        $resolved = $service->resolve($request);
        
        $this->assertEquals('guest', $resolved['type']);
        $this->assertMatchesRegularExpression('/^guest_[a-f0-9\-]{36}$/', $resolved['id']);
        $this->assertTrue($resolved['set_cookie']);
    }
    
    /** @test */
    public function it_generates_new_guest_id_if_cookie_invalid()
    {
        $service = new CartUserResolverService();
        
        $request = Request::create('/', 'GET');
        $request->cookies->set('guest_user_id', 'invalid_format');
        
        $resolved = $service->resolve($request);
        
        $this->assertEquals('guest', $resolved['type']);
        $this->assertNotEquals('invalid_format', $resolved['id']);
        $this->assertTrue($resolved['set_cookie']);
    }

    /** @test */
    public function it_resolves_authenticated_user()
    {
        // Mock Auth
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('id')->andReturn(123);
        
        $service = new CartUserResolverService();
        $request = Request::create('/', 'GET');
        
        $resolved = $service->resolve($request);
        
        $this->assertEquals('user', $resolved['type']);
        $this->assertEquals(123, $resolved['id']);
        $this->assertFalse($resolved['set_cookie']);
    }
}
