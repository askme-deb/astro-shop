<?php

namespace App\Services\Api;

use App\Services\Api\Clients\BaseApiClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\Api\CartUserResolverService;
use Illuminate\Http\Request;

/**
 * CartApiService
 *
 * Handles cart-related API operations: add-to-cart, buy-now.
 * Responsible for payload validation, cache invalidation, and response normalization.
 */
class CartApiService extends BaseApiClient
{
    protected CartUserResolverService $userResolverService;

    /**
     * Get product details for buyNow flow (for checkout pre-fill).
     * Returns product info and quantity for the checkout page.
     */
    public function getProductForBuyNow(int $productId, int $quantity = 1, Request $request = null): array
    {
        // You may have a product API endpoint, e.g. /api/v1/products/{id}
        $endpoint = "product/details/{$productId}";
        $options = [];
        $originalToken = $this->token;
        if ($request) {
            $userToken = $this->resolveUserToken($request);
            if ($userToken !== '') {
                $this->token = null;
                $options['headers'] = [
                    'Cookie' => 'shared_api_token=' . $userToken,
                ];
            } else {
                $resolved = $this->userResolverService->resolve($request);
                if (!empty($resolved['id'])) {
                    $options['query'] = ['guest_user_id' => $resolved['id']];
                }
                $this->token = null;
            }
        }
        $result = $this->request('GET', $endpoint, $options);
        $this->token = $originalToken;
        if ((isset($result['status']) && $result['status']) && isset($result['product'])) {
            // Attach quantity for buyNow
            $result['product']['quantity'] = $quantity;
            return $result['product'];
        }
        return [
            'product_id' => $productId,
            'quantity' => $quantity,
            'error' => $result['error'] ?? 'Product not found',
        ];
    }

    public function __construct(CartUserResolverService $userResolver)
    {
        parent::__construct();
        $this->userResolverService = $userResolver;
    }

    /**
     * Invalidate cart cache for a given method and key.
     */
    private function invalidateCache(string $method, string $key): void
    {
        $cacheKey = strtolower($method) . ':' . $key;
        Cache::forget($cacheKey);
        // Log::info('Cart cache invalidated', ['cache_key' => $cacheKey]);
    }

    /**
     * Resolve the user token from session (primary) or cookie (fallback).
     * Session is always reliable since verifyOtp stores token via Session::put().
     * Cookie may be unavailable on API routes depending on middleware stack.
     */
private function resolveUserToken(Request $request): string
{
    // auth_api_token cookie is excluded from EncryptCookies middleware
    // so it's always readable as plain raw Sanctum token
    $token = (string) $request->cookie('auth_api_token', '');

    // Log::info('resolveUserToken', [
    //     'cookie_token' => $token ? substr($token, 0, 20).'...' : 'NULL',
    // ]);

    return $token;
}

    /**
     * Set Bearer token and clean payload for logged-in user,
     * or resolve guest_user_id for guest.
     * Returns modified payload.
     */
private function applyAuthToRequest(array $payload, Request $request): array
{
    $userToken = $this->resolveUserToken($request);

    if ($userToken !== '') {
        $this->token = $userToken;
        unset($payload['guest_user_id'], $payload['user_id']);
    } else {
        $resolved = $this->userResolverService->resolve($request);
        if (! empty($resolved['id'])) {
            $payload['guest_user_id'] = $resolved['id'];
        }
        unset($payload['user_id']);
        $this->token = null;
    }

    return $payload;
}
    /**
     * Add product to cart. Resolves user/guest ID automatically.
     */
    public function addToCart(array $payload, Request $request = null): array
    {
        $endpoint = 'cart/add-to-cart';
        $originalToken = $this->token;
        $options = [];

        if ($request) {
            $userToken = $this->resolveUserToken($request);
            if ($userToken !== '') {
                // Logged-in user: send token as shared_api_token cookie
                $this->token = null; // Do not use Bearer
                $options['json'] = $payload;
                $options['headers'] = [
                    'Cookie' => 'shared_api_token=' . $userToken,
                ];
                unset($options['json']['guest_user_id'], $options['json']['user_id']);
            } else {
                // Guest: send guest_user_id, no token
                $resolved = $this->userResolverService->resolve($request);
                if (!empty($resolved['id'])) {
                    $payload['guest_user_id'] = $resolved['id'];
                }
                unset($payload['user_id']);
                $this->token = null;
                $options['json'] = $payload;
            }
        } else {
            $options['json'] = $payload;
        }

        $result = $this->request('POST', $endpoint, $options);

        $this->token = $originalToken;

        if ((isset($result['success']) && $result['success']) || (isset($result['status']) && $result['status'])) {
            $this->invalidateCache('GET', 'cart');
        }

        return $this->normalizeResponse($result);
    }

    /**
     * Buy now (direct purchase). Resolves user/guest ID automatically.
     */
    public function buyNow(array $payload, Request $request = null): array
    {
        $endpoint = '/api/v1/cart/buy-now';
        $originalToken = $this->token;

        if ($request) {
            $payload = $this->applyAuthToRequest($payload, $request);
        }

        $result = $this->request('POST', $endpoint, ['json' => $payload]);

        $this->token = $originalToken;

        if ((isset($result['success']) && $result['success']) || (isset($result['status']) && $result['status'])) {
            $this->invalidateCache('GET', 'cart');
        }

        return $this->normalizeResponse($result);
    }

    /**
     * Get the cart item count for a user or guest.
     */
    public function getCartCount(Request $request): int
    {
        try {
            $endpoint = 'cart/count';
            $originalToken = $this->token;
            $options = [];

            $userToken = $this->resolveUserToken($request);
            if ($userToken !== '') {
                // Logged-in user: send token as shared_api_token cookie
                $this->token = null;
                $options['headers'] = [
                    'Cookie' => 'shared_api_token=' . $userToken,
                ];
                // Remove guest_user_id and user_id from query if present
                if (isset($options['query'])) {
                    unset($options['query']['guest_user_id'], $options['query']['user_id']);
                }
            } else {
                // Guest: send guest_user_id, no token
                $resolved = $this->userResolverService->resolve($request);
                if (!empty($resolved['id'])) {
                    $options['query'] = ['guest_user_id' => $resolved['id']];
                }
                // Remove user_id if present
                if (isset($options['query']['user_id'])) {
                    unset($options['query']['user_id']);
                }
                $this->token = null;
            }

            $result = $this->request('GET', $endpoint, $options);

            $this->token = $originalToken;

            if ((isset($result['status']) && $result['status']) && isset($result['count'])) {
                return (int) $result['count'];
            }
        } catch (\Throwable $e) {
            Log::error('Failed to fetch cart count', ['error' => $e->getMessage()]);
        }

        return 0;
    }

    /**
     * Fetch cart data for user or guest.
     *
     * @return array{status: string, data: mixed, message: string}
     */
    public function getCart(Request $request): array
    {
        $endpoint = 'cart/products';

        try {
            $originalToken = $this->token;
            $options = [];

            $userToken = $this->resolveUserToken($request);
            if ($userToken !== '') {
                // Logged-in user: send token as shared_api_token cookie
                $this->token = null;
                $options['headers'] = [
                    'Cookie' => 'shared_api_token=' . $userToken,
                ];
            } else {
                // Guest: send guest_user_id, no token
                $resolved = $this->userResolverService->resolve($request);
                if (!empty($resolved['id'])) {
                    $options['query'] = ['guest_user_id' => $resolved['id']];
                }
                $this->token = null;
            }

            $result = $this->request('GET', $endpoint, $options);

            $this->token = $originalToken;

            if ((isset($result['status']) && $result['status']) && isset($result['data'])) {
                return [
                    'status'  => 'success',
                    'data'    => $result['data'],
                    'message' => 'Cart fetched successfully.',
                ];
            }

            Log::error('Cart API error', ['result' => $result]);

            return [
                'status'  => 'error',
                'data'    => null,
                'message' => 'Failed to fetch cart data.',
            ];
        } catch (\Throwable $e) {
            Log::error('Cart API Exception', ['exception' => $e]);

            return [
                'status'  => 'error',
                'data'    => null,
                'message' => 'Cart service unavailable.',
            ];
        }
    }

    /**
     * Fetch cart data for a specific guest ID.
     * Useful for merging carts after login.
     */
    public function getGuestCart(string $guestUserId): array
    {
        $endpoint = 'cart/products';

        try {
            $options = [
                'query' => ['guest_user_id' => $guestUserId],
            ];
            $result = $this->request('GET', $endpoint, $options);

            if ((isset($result['status']) && $result['status']) && isset($result['data'])) {
                return [
                    'status'  => 'success',
                    'data'    => $result['data'],
                    'message' => 'Cart fetched successfully.',
                ];
            }

            return [
                'status'  => 'error',
                'data'    => null,
                'message' => 'Failed to fetch cart data.',
            ];
        } catch (\Throwable $e) {
            Log::error('Cart API Exception', ['exception' => $e]);

            return [
                'status'  => 'error',
                'data'    => null,
                'message' => 'Cart service unavailable.',
            ];
        }
    }

    /**
     * Update cart item quantity.
     */
    public function updateCartQuantity(array $payload, Request $request = null): array
    {
        $endpoint = 'update/cart/quantity';
        $originalToken = $this->token;
        $options = [];

        if ($request) {
            $userToken = $this->resolveUserToken($request);
            //   Log::info('updateCartQuantity token check', [
            //     'userToken' => $userToken,
            //     'payload' => $payload,
            // ]);
            if ($userToken !== '') {
                // Logged-in user: send token as shared_api_token cookie
                $this->token = null;
                $options['json'] = $payload;
                $options['headers'] = [
                    'Cookie' => 'shared_api_token=' . $userToken,
                ];
                unset($options['json']['guest_user_id'], $options['json']['user_id']);
            } else {
                // Guest: send guest_user_id, no token
                $resolved = $this->userResolverService->resolve($request);
                if (!empty($resolved['id'])) {
                    $payload['guest_user_id'] = $resolved['id'];
                }
                unset($payload['user_id']);
                $this->token = null;
                $options['json'] = $payload;
            }
        } else {
            $options['json'] = $payload;
        }

        $result = $this->request('POST', $endpoint, $options);

        $this->token = $originalToken;

        if ((isset($result['success']) && $result['success']) || (isset($result['status']) && $result['status'])) {
            $this->invalidateCache('GET', 'cart');
        }

        return $this->normalizeResponse($result);
    }

    /**
     * Delete item from cart.
     */
    public function deleteCartItem(array $payload, Request $request = null): array
    {
        $endpoint = 'delete/cart/item';
        $originalToken = $this->token;
        $options = [];
 
     
        if ($request) {
           // dd('deleteCartItem payload');
            $userToken = $this->resolveUserToken($request);
            // Log::info('deleteCartItem token check', [
            //     'userToken' => $userToken,
            //     'payload' => $payload,
            // ]);
            if ($userToken !== '') {
                // Logged-in user: send token as shared_api_token cookie
                $this->token = null; // Do not use Bearer
                $options['json'] = $payload;
                $options['headers'] = [
                    'Cookie' => 'shared_api_token=' . $userToken,
                ];
                unset($options['json']['guest_user_id'], $options['json']['user_id']);
            } else {
                // Guest: send guest_user_id, no token
                $resolved = $this->userResolverService->resolve($request);
                if (!empty($resolved['id'])) {
                    $payload['guest_user_id'] = $resolved['id'];
                }
                unset($payload['user_id']);
                $this->token = null;
                $options['json'] = $payload;
            }
        } else {
            $options['json'] = $payload;
        }



        $result = $this->request('POST', $endpoint, $options);

        $this->token = $originalToken;

        if ((isset($result['success']) && $result['success']) || (isset($result['status']) && $result['status'])) {
            $this->invalidateCache('GET', 'cart');
        }

        return $this->normalizeResponse($result);
    }

    /**
     * Normalize API response for frontend consumption.
     */
    protected function normalizeResponse(array $result): array
    {
        $success = $result['success'] ?? $result['status'] ?? false;

        return [
            'success' => $success,
            'data'    => $result['data'] ?? null,
            'error'   => $result['error'] ?? null,
            'errors'  => $result['errors'] ?? null,
            'status'  => $success,
            'message' => $result['message'] ?? null,
        ];
    }
}