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
    public function __construct(
        protected CartApiService $cartApiService,
        protected CouponApiService $couponApiService
    ) {
    }

    /**
     * Get coupons applicable for the current cart.
     *
     * Route: GET /api/coupons
     */
    public function index(Request $request): JsonResponse
    {
        $orderTotal = 0.0;
        $productIds = [];
        $categoryIds = [];

        try {
            // Try to fetch cart so we can derive filters, but don't fail
            // coupon listing if the cart API has issues.
            $cartResult = $this->cartApiService->getCart($request);

            if (($cartResult['status'] ?? 'error') === 'success') {
                $items = $cartResult['data'] ?? [];

                if (is_array($items)) {
                    foreach ($items as $item) {
                        if (! is_array($item)) {
                            continue;
                        }

                        $product = $item['product'] ?? [];
                        if (is_array($product)) {
                            if (isset($product['id'])) {
                                $productIds[] = $product['id'];
                            }
                            if (isset($product['category_id'])) {
                                $categoryIds[] = $product['category_id'];
                            }
                        }

                        $unitPrice = 0.0;
                        if (is_array($product) && isset($product['total_price'])) {
                            $unitPrice = (float) $product['total_price'];
                        } elseif (isset($item['amount'])) {
                            $unitPrice = (float) $item['amount'];
                        }

                        $quantity = (int) ($item['quantity'] ?? 1);
                        if ($quantity < 1) {
                            $quantity = 1;
                        }

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
        if ($orderTotal > 0) {
            $filters['order_total'] = $orderTotal;
        }
        if (! empty($productIds)) {
            // External API expects product_id[] style filters.
            // Passing an array here will be encoded as product_id[]=1&product_id[]=2.
            $filters['product_id'] = array_values(array_unique($productIds));
        }
        if (! empty($categoryIds)) {
            // Same for category_id[] filters.
            $filters['category_id'] = array_values(array_unique($categoryIds));
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
}
