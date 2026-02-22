<?php

namespace App\Services\Api;

use App\Services\Api\Clients\BaseApiClient;
use Illuminate\Support\Facades\Log;

/**
 * CouponApiService
 *
 * Wrapper around the external Astro API coupons endpoint.
 * Uses BaseApiClient for HTTP concerns and exposes a small,
 * normalized response for the application to consume.
 */
class CouponApiService extends BaseApiClient
{
    /**
     * Remove a coupon for the given user/guest.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function removeCoupon(array $data): array
    {
        $request = request();
        $userToken = $this->resolveUserToken($request);
        $options = [];
        if ($userToken !== '') {
            // Logged-in user: send token as shared_api_token cookie
            $this->token = null;
            $options['json'] = $data;
            $options['headers'] = [
                'Cookie' => 'shared_api_token=' . $userToken,
            ];
            unset($options['json']['guest_user_id'], $options['json']['user_id']);
        } else {
            // Guest: send guest_user_id, no token
            $resolved = $this->userResolver->resolve($request);
            if (!empty($resolved['id'])) {
                $data['guest_user_id'] = $resolved['id'];
            }
            unset($data['user_id']);
            $this->token = null;
            $options['json'] = $data;
        }
        try {
            $response = $this->request('POST', 'remove-coupon', $options);
        } catch (\Throwable $exception) {
            Log::error('Remove coupon API call failed', [
                'service' => static::class,
                'endpoint' => 'remove-coupon',
                'message' => $exception->getMessage(),
            ]);
            return [
                'status' => false,
                'message' => 'Coupon service temporarily unavailable.',
                'data' => null,
            ];
        }

        if (! is_array($response)) {
            return [
                'status' => false,
                'message' => 'Unexpected coupon response.',
                'data' => null,
            ];
        }

        return $response;
    }
    /**
     * Astro API coupons endpoint (relative to base_url).
     *
     * @var string
     */
    protected string $endpoint = 'coupons';

    protected CartUserResolverService $userResolver;

    public function __construct(CartUserResolverService $userResolver)
    {
        parent::__construct();
        $this->userResolver = $userResolver;
    }

    /**
     * Fetch coupons from the external API.
     *
     * @param array<string, mixed> $filters Optional filters such as product_id, category_id, order_total
     * @return array{status: bool, coupons: array<int, array<string, mixed>>, message: string}
     */
    public function getCoupons(array $filters = []): array
    {
        try {
            // Resolve user for coupon context
            $request = request();
            $userToken = $this->resolveUserToken($request);
            $options = [];
            if ($userToken !== '') {
                // Logged-in user: send token as shared_api_token cookie
                $this->token = null;
                $options['headers'] = [
                    'Cookie' => 'shared_api_token=' . $userToken,
                ];
                unset($filters['guest_user_id'], $filters['user_id']);
            } else {
                // Guest: send guest_user_id, no token
                $resolved = $this->userResolver->resolve($request);
                if (!empty($resolved['id'])) {
                    $filters['guest_user_id'] = $resolved['id'];
                }
                unset($filters['user_id']);
                $this->token = null;
            }
            $options['query'] = $filters;
            $data = $this->request('GET', $this->endpoint, $options);
        } catch (\Throwable $exception) {
            Log::error('Coupon API call failed', [
                'service' => static::class,
                'endpoint' => $this->endpoint,
                'message' => $exception->getMessage(),
            ]);

            // Try to extract message from exception if available
            $apiMessage = method_exists($exception, 'getMessage') ? $exception->getMessage() : 'Coupon service temporarily unavailable.';
            return [
                'status' => false,
                'coupons' => [],
                'message' => $apiMessage,
            ];
        }

        if (! is_array($data)) {
            return [
                'status' => false,
                'coupons' => [],
                'message' => 'Unexpected coupon response.',
            ];
        }

        $status = (bool) ($data['status'] ?? true);
        $message = (string) ($data['message'] ?? ($status ? 'Coupons fetched successfully.' : 'Failed to load coupons.'));

        // The API may return coupons in various wrappers – try to unwrap sensibly.
        $rawCoupons = $data['data'] ?? $data['coupons'] ?? [];

        if (is_array($rawCoupons) && isset($rawCoupons['data']) && is_array($rawCoupons['data'])) {
            $rawCoupons = $rawCoupons['data'];
        } elseif (is_array($rawCoupons) && isset($rawCoupons['coupons']) && is_array($rawCoupons['coupons'])) {
            $rawCoupons = $rawCoupons['coupons'];
        }

        if (! is_array($rawCoupons)) {
            $rawCoupons = [];
        }

        $coupons = [];
        foreach ($rawCoupons as $coupon) {
            if (is_array($coupon)) {
                $coupons[] = $coupon;
            }
        }

        return [
            'status' => $status,
            'coupons' => $coupons,
            'message' => $message,
        ];
    }

    /**
     * Apply a coupon code for the given user/guest.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function applyCoupon(array $data): array
    {
        $request = request();
        $userToken = $this->resolveUserToken($request);
        $options = [];
        if ($userToken !== '') {
            // Logged-in user: send token as shared_api_token cookie
            $this->token = null;
            $options['json'] = $data;
            $options['headers'] = [
                'Cookie' => 'shared_api_token=' . $userToken,
            ];
            unset($options['json']['guest_user_id'], $options['json']['user_id']);
        } else {
            // Guest: send guest_user_id, no token
            $resolved = $this->userResolver->resolve($request);
            if (!empty($resolved['id'])) {
                $data['guest_user_id'] = $resolved['id'];
            }
            unset($data['user_id']);
            $this->token = null;
            $options['json'] = $data;
        }
        try {
            $response = $this->request('POST', 'apply-coupon', $options);
        } catch (\Throwable $exception) {
            Log::error('Apply coupon API call failed', [
                'service' => static::class,
                'endpoint' => 'apply-coupon',
                'message' => $exception->getMessage(),
            ]);

            return [
                'status' => false,
                'message' => 'Coupon service temporarily unavailable.',
                'data' => null,
            ];
        }

        if (! is_array($response)) {
            return [
                'status' => false,
                'message' => 'Unexpected coupon response.',
                'data' => null,
            ];
        }

        return $response;

    }

    /**
     * Resolve the user token from session or cookie.
     */
    private function resolveUserToken($request): string
    {
        $token = (string) $request->cookie('auth_api_token', '');
        return $token;
    }
}
