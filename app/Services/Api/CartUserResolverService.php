<?php

namespace App\Services\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class CartUserResolverService
{
    protected string $cookieName = 'guest_user_id';
    protected int $cookieMinutes = 60 * 24 * 30; // 30 days

    protected ?array $resolved = null;

    /**
     * Resolve the cart user identifier (user_id or guest_user_id)
     *
     * @param Request $request
     * @return array ['type' => 'user'|'guest', 'id' => string|int, 'set_cookie' => bool]
     */
    public function resolve(Request $request): array
    {
        if ($this->resolved) {
            return $this->resolved;
        }

        if (Auth::check()) {
            $this->resolved = [
                'type' => 'user',
                'id' => Auth::id(),
                'set_cookie' => false,
            ];
            return $this->resolved;
        }

        // Laravel automatically decrypts encrypted cookies when reading
        $guestId = $request->cookie($this->cookieName);
        


        $valid = false;

        if (is_string($guestId) && preg_match('/^guest_[a-f0-9\-]{36}$/', $guestId)) {
            $valid = true;
        }

        if (!$valid) {

            $guestId = $this->generateGuestId();
        }

        $this->resolved = [
            'type' => 'guest',
            'id' => $guestId,
            'set_cookie' => !$valid,
        ];

        return $this->resolved;
    }

    /**
     * Generate a unique guest user ID
     */
    public function generateGuestId(): string
    {
        // Format: guest_<uuid>
        return 'guest_' . Str::uuid();
    }

    /**
     * Create a cookie for the guest user ID
     */
    public function makeGuestCookie(string $guestId)
    {
        // Secure, HttpOnly, SameSite, encrypted
        $secure = request()->isSecure();
        return Cookie::make(
            $this->cookieName,
            // Let Laravel handle encryption automatically
            $guestId,
            $this->cookieMinutes,
            '/',
            null,
            $secure,
            true, // HttpOnly
            false,
            'Lax' // SameSite
        );
    }

    /**
     * Forget the guest user cookie
     */
    public function forgetGuestCookie()
    {
        return Cookie::forget($this->cookieName);
    }

    /**
     * Get the cookie name
     */
    public function getCookieName(): string
    {
        return $this->cookieName;
    }
}
