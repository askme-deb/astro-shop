<?php

namespace App\Services\Api;

use App\Services\Api\Clients\BaseApiClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * CartApiService
 *
 * Handles cart-related API operations: add-to-cart, buy-now.
 * Responsible for payload validation, cache invalidation, and response normalization.
 */
class CartApiService extends BaseApiClient
{
    /**
     * Invalidate cart cache for a given method and key.
     *
     * @param string $method
     * @param string $key
     * @return void
     */
    private function invalidateCache(string $method, string $key): void
    {
        // You may need to adjust the cache key logic to match your cache implementation
        $cacheKey = strtolower($method) . ':' . $key;
        Cache::forget($cacheKey);
        // Optionally, log cache invalidation
        Log::info('Cart cache invalidated', ['cache_key' => $cacheKey]);
    }
// ...existing code...
    /**
     * Add product to cart.
     *
     * @param array $payload
     * @return array
     */
    /**
     * Add product to cart via Astro API.
     * Validates payload, calls API, and invalidates cart cache.
     *
     * @param array $payload
     * @return array
     */
    // public function addToCart(array $payload): array
    // {
    //     $validator = Validator::make($payload, [
    //         'product_id' => 'required|integer',
    //         'quantity' => 'required|integer|min:1',
    //         // Add more validation rules as needed
    //     ]);

    //     if ($validator->fails()) {
    //         return [
    //             'success' => false,
    //             'error' => 'Validation failed',
    //             'errors' => $validator->errors()->toArray(),
    //             'status' => 422,
    //         ];
    //     }

    //     $endpoint = 'cart/add-to-cart';
    //     $result = $this->request('POST', $endpoint, ['json' => $payload]);
    //     //dd($result);
    //     // Invalidate cart cache on update
    //     if (isset($result['success']) && $result['success']) {
    //         $this->invalidateCache('GET', 'cart');
    //     }

    //     return $this->normalizeResponse($result);
    // }

    public function addToCart(array $payload): array
    {
        $endpoint = 'cart/add-to-cart';
        $result = $this->request('POST', $endpoint, ['json' => $payload]);

        // Invalidate cart cache on update
        if (isset($result['success']) && $result['success']) {
            $this->invalidateCache('GET', 'cart');
        }
        // Also check for 'status' key
        if (isset($result['status']) && $result['status']) {
            $this->invalidateCache('GET', 'cart');
        }

        return $this->normalizeResponse($result);
    }


    /**
     * Buy now (direct purchase).
     *
     * @param array $payload
     * @return array
     */
    /**
     * Buy now (direct purchase) via Astro API.
     * Validates payload, calls API, and invalidates cart cache.
     *
     * @param array $payload
     * @return array
     */
    public function buyNow(array $payload): array
    {
        $validator = Validator::make($payload, [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            // Add more validation rules as needed
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()->toArray(),
                'status' => 422,
            ];
        }

        $endpoint = '/api/v1/cart/buy-now';
        $result = $this->request('POST', $endpoint, ['json' => $payload]);

        // Invalidate cart cache on update
        if ($result['success']) {
            $this->invalidateCache('GET', '/api/v1/cart');
        }

        return $this->normalizeResponse($result);
    }

    /**
     * Normalize API response for frontend consumption.
     *
     * @param array $response
     * @return array
     */
    protected function normalizeResponse(array $result): array
    {
        // If API uses 'status' instead of 'success', map it
        $success = $result['success'] ?? $result['status'] ?? false;

        return [
            'success' => $success,
            'data' => $result['data'] ?? null,
            'error' => $result['error'] ?? null,
            'errors' => $result['errors'] ?? null,
            'status' => $success,
            'message' => $result['message'] ?? null, // Don't lose the message!
        ];
    }

        /**
     * Get the cart item count for a guest user.
     *
     * @param string $guestUserId
     * @return int
     */
    public function getCartCount(string $guestUserId): int
    {
        try {
            $endpoint = 'cart/count';
            $result = $this->request('GET', $endpoint, [
                'query' => ['guest_user_id' => $guestUserId],
            ]);
            if ((isset($result['status']) && $result['status']) && isset($result['count'])) {
                return (int) $result['count'];
            }
            Log::warning('Cart count API returned false status or missing count', ['result' => $result]);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch cart count', ['error' => $e->getMessage()]);
        }
        return 0;
    }

        /**
     * Fetch cart data from external API.
     *
     * @param string $guestUserId
     * @return array{status: string, data: mixed, message: string}
     */
    public function getCart(string $guestUserId): array
    {
        $endpoint = 'carts';
        try {
            $result = $this->request('GET', $endpoint, [
                'query' => ['guest_user_id' => $guestUserId],
            ]);
            dd($result);
            if ((isset($result['status']) && $result['status']) && isset($result['data'])) {
                return [
                    'status' => 'success',
                    'data' => $result['data'],
                    'message' => 'Cart fetched successfully.',
                ];
            }
            Log::error('Cart API error', [
                'result' => $result,
            ]);
            return [
                'status' => 'error',
                'data' => null,
                'message' => 'Failed to fetch cart data.',
            ];
        } catch (\Throwable $e) {
            Log::error('Cart API Exception', ['exception' => $e]);
            return [
                'status' => 'error',
                'data' => null,
                'message' => 'Cart service unavailable.',
            ];
        }
    }
}
