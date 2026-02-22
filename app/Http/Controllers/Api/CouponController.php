<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\CartApiService;
use App\Services\Api\CouponApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * CouponController
 *
 * Thin API controller for exposing coupons relevant to the
 * current cart/order. It delegates external HTTP calls to
 * CouponApiService and cart fetching to CartApiService.
 */
class CouponController extends Controller
{

    protected CartApiService $cartApiService;
    protected CouponApiService $couponApiService;
    protected \App\Services\Api\CartUserResolverService $cartUserResolverService;

    public function __construct(
        CartApiService $cartApiService,
        CouponApiService $couponApiService,
        \App\Services\Api\CartUserResolverService $cartUserResolverService
    ) {
        $this->cartApiService = $cartApiService;
        $this->couponApiService = $couponApiService;
        $this->cartUserResolverService = $cartUserResolverService;
    }

    /**
     * Get coupons applicable for the current cart.
     * Route: GET /api/coupons
     */
    public function index(Request $request): JsonResponse
    {
        $orderTotal = 0.0;
        $productIds = [];
        $categoryIds = [];

        $resolved = $this->cartUserResolverService->resolve($request);

        try {
            $cartResult = $this->cartApiService->getCart($request);
            if (($cartResult['status'] ?? 'error') === 'success') {
                $items = $cartResult['data'] ?? [];
                if (is_array($items)) {
                    foreach ($items as $item) {
                        if (!is_array($item)) continue;
                        $product = $item['product'] ?? [];
                        if (is_array($product)) {
                            if (isset($product['id'])) $productIds[] = $product['id'];
                            if (isset($product['category_id'])) $categoryIds[] = $product['category_id'];
                        }
                        $unitPrice = 0.0;
                        if (is_array($product) && isset($product['total_price'])) {
                            $unitPrice = (float) $product['total_price'];
                        } elseif (isset($item['amount'])) {
                            $unitPrice = (float) $item['amount'];
                        }
                        $quantity = (int) ($item['quantity'] ?? 1);
                        if ($quantity < 1) $quantity = 1;
                        $orderTotal += $unitPrice * $quantity;
                    }
                }
            }
        } catch (\Throwable $exception) {
            Log::error('Failed to derive cart filters for coupons', [
                'message' => $exception->getMessage(),
            ]);
        }

        $filters = [];
        if ($orderTotal > 0) $filters['order_total'] = $orderTotal;
        if (!empty($productIds)) $filters['product_id'] = array_values(array_unique($productIds));
        if (!empty($categoryIds)) $filters['category_id'] = array_values(array_unique($categoryIds));
        if ($resolved['type'] === 'user') {
            $filters['user_id'] = $resolved['id'];
        } else {
            $filters['guest_user_id'] = $resolved['id'];
        }

        $couponResult = $this->couponApiService->getCoupons($filters);
        $status = (bool) ($couponResult['status'] ?? false);
        $httpStatus = $status ? 200 : 502;

        return response()->json([
            'status' => $status,
            'message' => $couponResult['message'] ?? ($status ? 'Coupons fetched successfully.' : 'Unable to fetch coupons.'),
            'coupons' => $couponResult['coupons'] ?? [],
        ], $httpStatus);
    }

    /**
     * Apply a coupon code for the current user/guest.
     * Route: POST /api/apply-coupon
     */
    public function apply(Request $request): JsonResponse
    {
        $payload = $request->all();
        $resolved = $this->cartUserResolverService->resolve($request);
        if ($resolved['type'] === 'user') {
            $payload['user_id'] = $resolved['id'];
        } else {
            $payload['guest_user_id'] = $resolved['id'];
        }
        $result = $this->couponApiService->applyCoupon($payload);
        $status = (bool) ($result['status'] ?? false);
        $httpStatus = $status ? 200 : 502;
        return response()->json([
            'status' => $status,
            'message' => $result['message'] ?? ($status ? 'Coupon applied successfully.' : 'Unable to apply coupon.'),
            'data' => $result['data'] ?? null,
        ], $httpStatus);
    }
        /**
     * Remove a coupon for the current user/guest.
     * Route: POST /api/v1/remove-coupon
     */
    public function remove(Request $request): JsonResponse
    {
        $payload = $request->all();
        $resolved = $this->cartUserResolverService->resolve($request);
        if ($resolved['type'] === 'user') {
            $payload['user_id'] = $resolved['id'];
        } else {
            $payload['guest_user_id'] = $resolved['id'];
        }
        $result = $this->couponApiService->removeCoupon($payload);
        $status = (bool) ($result['status'] ?? false);
        $httpStatus = $status ? 200 : 502;
        return response()->json([
            'status' => $status,
            'message' => $result['message'] ?? ($status ? 'Coupon removed successfully.' : 'Unable to remove coupon.'),
            'data' => $result['data'] ?? null,
        ], $httpStatus);
    }
}
