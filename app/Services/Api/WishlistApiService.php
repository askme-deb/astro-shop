<?php

namespace App\Services\Api;

use App\Services\Api\Clients\BaseApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Api\CartUserResolverService;

/**
 * WishlistApiService
 *
 * Thin wrapper around the Astro wishlist endpoints.
 * Uses BaseApiClient for HTTP concerns and CartUserResolverService
 * to handle guest vs authenticated users.
 */
class WishlistApiService extends BaseApiClient
{
    protected CartUserResolverService $userResolver;

    public function __construct(CartUserResolverService $userResolver)
    {
        parent::__construct();
        $this->userResolver = $userResolver;
    }

    /**
     * Get wishlist item count for current user/guest.
     */
    public function getWishlistCount(Request $request): int
    {
        try {
            $originalToken = $this->token;

            $userToken = (string) $request->cookie('auth_api_token', '');
            $options = [];

            if ($userToken !== '') {
                // Logged-in API user: use bearer token, no ids in query
                $this->token = $userToken;
            } else {
                // Guest: include guest_user_id in query
                $resolved = $this->userResolver->resolve($request);
                if ($resolved['type'] === 'guest') {
                    $options['query'] = ['guest_user_id' => $resolved['id']];
                }
            }

            $data = $this->request('GET', 'wishlist/count', $options);

            $this->token = $originalToken;
        } catch (\Throwable $exception) {
            Log::error('Wishlist count API call failed', [
                'service' => static::class,
                'message' => $exception->getMessage(),
            ]);

            return 0;
        }

        if (! is_array($data)) {
            return 0;
        }

        if (isset($data['count'])) {
            return (int) $data['count'];
        }

        if (isset($data['data']['count'])) {
            return (int) $data['data']['count'];
        }

        return 0;
    }

    /**
     * Check if a product is in the wishlist for current user/guest.
     *
     * External endpoint: POST /api/v1/wishlist/check
     *
     * @return bool|null  true/false if known, null on failure
     */
    public function checkWishlist(int $productId, Request $request): ?bool
    {
        $payload = [
            'product_id' => $productId,
        ];
        try {
            $originalToken = $this->token;

            $userToken = (string) $request->cookie('auth_api_token', '');

            if ($userToken !== '') {
                $this->token = $userToken;
                // No guest_user_id in payload when using bearer token
            } else {
                $resolved = $this->userResolver->resolve($request);
                if ($resolved['type'] === 'guest') {
                    $payload['guest_user_id'] = $resolved['id'];
                }
            }

            $data = $this->request('POST', '/api/v1/wishlist/check', [
                'json' => $payload,
            ]);

            $this->token = $originalToken;
        } catch (\Throwable $exception) {
            Log::error('Wishlist check API call failed', [
                'service' => static::class,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }

        if (! is_array($data)) {
            return null;
        }

        $status = (bool) ($data['status'] ?? false);

        if (! $status) {
            return null;
        }

        if (array_key_exists('in_wishlist', $data)) {
            return (bool) $data['in_wishlist'];
        }

        return null;
    }

    /**
     * Toggle a product in the wishlist for the current user/guest.
     *
     * External endpoint: POST wishlist/add-to-wishlist
     *
     * Behaviour:
     * - Uses CartUserResolverService to resolve either a user_id or guest_user_id.
     * - Does not rely on Laravel's session within API routes.
     *
     * @param int $productId
     * @param Request $request
     * @return array{success: bool, message: string, in_wishlist: bool|null}
     */
    public function toggleWishlist(int $productId, Request $request): array
    {
        $payload = [
            'product_id' => $productId,
        ];

        try {
            $originalToken = $this->token;

            $userToken = (string) $request->cookie('auth_api_token', '');

            if ($userToken !== '') {
                $this->token = $userToken;
                unset($payload['guest_user_id'], $payload['user_id']);
            } else {
                $resolved = $this->userResolver->resolve($request);
                if ($resolved['type'] === 'guest') {
                    $payload['guest_user_id'] = $resolved['id'];
                    unset($payload['user_id']);
                }
            }

            $endpoint = 'wishlist/add-to-wishlist';

            $result = $this->request('POST', $endpoint, [
                'json' => $payload,
            ]);

            $this->token = $originalToken;
        } catch (\Throwable $exception) {
            Log::error('Wishlist API call failed', [
                'service' => static::class,
                'message' => $exception->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Unable to update wishlist at the moment.',
                'in_wishlist' => null,
            ];
        }

        $success = (bool) ($result['success'] ?? $result['status'] ?? false);
        $message = (string) ($result['message'] ?? ($success
            ? 'Wishlist updated successfully.'
            : 'Failed to update wishlist.')
        );
        // Prefer authoritative state from the new check endpoint
        $inWishlist = $this->checkWishlist($productId, $request);

        return [
            'success' => $success,
            'message' => $message,
            'in_wishlist' => $inWishlist,
        ];
    }
}
