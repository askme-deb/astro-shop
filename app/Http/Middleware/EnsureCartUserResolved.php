<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Api\CartUserResolverService;
use Illuminate\Support\Facades\Cookie;

class EnsureCartUserResolved
{
    protected CartUserResolverService $resolver;

    public function __construct(CartUserResolverService $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Handle an incoming request.
     * Ensures a guest_user_id cookie exists for guests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $resolved = $this->resolver->resolve($request);
        if ($resolved['type'] === 'guest' && ($resolved['set_cookie'] ?? false)) {
            $cookie = $this->resolver->makeGuestCookie($resolved['id']);
            $response = $next($request);
            return $response->cookie($cookie);
        }
        return $next($request);
    }
}
