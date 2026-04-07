<?php

namespace App\Services\Api;

use App\Services\Api\CouponApiService;
use Illuminate\Support\Facades\Cache;

class ProductCouponService
{
    protected CouponApiService $couponApiService;

    public function __construct(CouponApiService $couponApiService)
    {
        $this->couponApiService = $couponApiService;
    }

    /**
     * Fetch coupons applicable to a specific product, with user/guest context.
     *
     * @param int $productId
     * @param int|null $categoryId
     * @param float|null $orderTotal
     * @return array<int, array<string, mixed>>
     */
    public function getProductCoupons(int $productId, ?int $categoryId = null, ?float $orderTotal = null): array
    {
        $filters = [
            'product_id' => $productId,
        ];
        if ($categoryId) {
            $filters['category_id'] = $categoryId;
        }
        if ($orderTotal) {
            $filters['order_total'] = $orderTotal;
        }
        // Add user/guest context
        $request = request();
        $userId = $request->user()?->id;
        $guestUserId = $request->cookie('guest_user_id');
        if ($userId) {
            $filters['user_id'] = $userId;
        } elseif ($guestUserId) {
            $filters['guest_user_id'] = $guestUserId;
        }

        $cacheKey = $this->cacheKeyForCoupons($filters);
        $ttl = (int) config('services.astrorajumaharaj.cache_ttl', 300);

        $result = Cache::store()->remember($cacheKey, $ttl, function () use ($filters) {
            return $this->couponApiService->getCoupons($filters);
        });

        return $result['coupons'] ?? [];
    }

    /**
     * Build a deterministic cache key for product coupon lookups.
     *
     * @param array<string, mixed> $filters
     */
    protected function cacheKeyForCoupons(array $filters): string
    {
        ksort($filters);

        return 'astro.product.coupons.' . md5(json_encode($filters));
    }
}
