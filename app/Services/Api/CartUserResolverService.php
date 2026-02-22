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
     * On the external API, guest_user_id is expected to be an integer,
     * so we generate and persist a numeric ID for guests.
     *
     * @param Request $request
     * @return array ['type' => 'user'|'guest', 'id' => int, 'set_cookie' => bool]
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

        // External API expects an integer guest_user_id, so accept only digits.
        if (is_numeric($guestId) && (int) $guestId > 0) {
            $valid = true;
        }

        if (! $valid) {
            $guestId = $this->generateGuestId();
        }

        $this->resolved = [
            'type' => 'guest',
            'id' => (int) $guestId,
            'set_cookie' => ! $valid,
        ];

        return $this->resolved;
    }

    /**
     * Generate a unique numeric guest user ID.
     *
     * The external API expects guest_user_id to be an integer, so we
     * generate a random positive integer and store it as the cookie value.
     */
    public function generateGuestId(): int
    {
        return random_int(100000000, 999999999);
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
