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
     * Astro API coupons endpoint (relative to base_url).
     *
     * @var string
     */
    protected string $endpoint = 'coupons';

    /**
     * Fetch coupons from the external API.
     *
     * @param array<string, mixed> $filters Optional filters such as product_id, category_id, order_total
     * @return array{status: bool, coupons: array<int, array<string, mixed>>, message: string}
     */
    public function getCoupons(array $filters = []): array
    {
        try {
            $data = $this->request('GET', $this->endpoint, [
                'query' => $filters,
            ]);
           // dd($data);
        } catch (\Throwable $exception) {
            Log::error('Coupon API call failed', [
                'service' => static::class,
                'endpoint' => $this->endpoint,
                'message' => $exception->getMessage(),
            ]);

            return [
                'status' => false,
                'coupons' => [],
                'message' => 'Coupon service temporarily unavailable.',
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

        // Common shapes we might see:
        // - { status, data: [ {..coupon..}, ... ] }
        // - { status, data: { coupons: [..] } }
        // - { status, coupons: [..] }
        if (is_array($rawCoupons) && isset($rawCoupons['data']) && is_array($rawCoupons['data'])) {
            $rawCoupons = $rawCoupons['data'];
        } elseif (is_array($rawCoupons) && isset($rawCoupons['coupons']) && is_array($rawCoupons['coupons'])) {
            $rawCoupons = $rawCoupons['coupons'];
        }

        if (! is_array($rawCoupons)) {
            $rawCoupons = [];
        }

        // Ensure a simple list of coupon arrays.
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
        try {
            // The BaseApiClient base URL already includes the API version
            // (e.g. /api/v1), so we only append the relative path here.
            $response = $this->request('POST', 'apply-coupon', [
                'json' => $data,
            ]);
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
}
