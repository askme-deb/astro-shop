<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\CartApiService;
use App\Services\Api\CartUserResolverService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Traits\WithSweetAlert;
use Illuminate\Support\Facades\Validator;

/**
 * CartController
 *
 * Thin controller for cart API endpoints.
 * Delegates business logic to CartApiService.
 */
class CartController extends Controller
{
    use WithSweetAlert;

    protected CartApiService $cartApiService;
    protected CartUserResolverService $cartUserResolverService;

    public function __construct(CartApiService $cartApiService, CartUserResolverService $cartUserResolverService)
    {
        $this->cartApiService = $cartApiService;
        $this->cartUserResolverService = $cartUserResolverService;
    }

    public function addToCart(Request $request): JsonResponse
    {
        $payload = $request->all();
        $validator = Validator::make($payload, [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()->toArray(),
                'status' => 422,
            ], 422);
        }
        $result = $this->cartApiService->addToCart($payload, $request);
        $resolved = $this->cartUserResolverService->resolve($request);
        $message = $result['message'] ?? ($result['success'] ? 'Product added to cart successfully' : 'Failed to add to cart');
        $response = response()->json([
            'success' => $result['success'],
            'message' => $message,
            'data' => $result['data'] ?? null,
            'error' => $result['error'] ?? null,
            'errors' => $result['errors'] ?? null,
            'status' => $result['status'] ?? false,
            'resolved' => $resolved, // Debug info
        ]);
        return $response;
    }

    public function buyNow(Request $request): JsonResponse
    {
        $payload = $request->all();
        // Step 1: Initial buyNow (product page) - only product_id and quantity required
        $initialValidator = Validator::make($payload, [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);
        if ($initialValidator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $initialValidator->errors()->toArray(),
                'status' => 422,
            ], 422);
        }

        // Step 2: If address_id and payment_method are present, validate full checkout
        if (isset($payload['address_id']) && isset($payload['payment_method'])) {
            $checkoutValidator = Validator::make($payload, [
                'address_id' => 'required|integer',
                'payment_method' => 'required|string',
                // Optional fields: variation_id, carat, coupon_code, etc.
            ]);
            if ($checkoutValidator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'errors' => $checkoutValidator->errors()->toArray(),
                    'status' => 422,
                ], 422);
            }
            // Optionally validate variation_id, carat, coupon_code, etc. if present
            if (isset($payload['variation_id']) && !is_array($payload['variation_id'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'variation_id must be an array',
                    'status' => 422,
                ], 422);
            }
            if (isset($payload['carat']) && !is_numeric($payload['carat'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'carat must be numeric',
                    'status' => 422,
                ], 422);
            }
            $resolved = $this->cartUserResolverService->resolve($request);
            $result = $this->cartApiService->buyNow($payload, $request);
            // Compose detailed response for frontend
            $response = response()->json([
                'success' => $result['success'],
                'message' => $result['message'] ?? ($result['success'] ? 'Buy now successful' : 'Buy now failed'),
                'data' => $result['data'] ?? null,
                'error' => $result['error'] ?? null,
                'errors' => $result['errors'] ?? null,
                'status' => $result['status'] ?? false,
                'resolved' => $resolved,
            ]);
            return $response;
        }

        // If address_id/payment_method missing, return product info for checkout page
        // (Frontend should redirect to checkout and let user fill address/payment)
        $resolved = $this->cartUserResolverService->resolve($request);
        // Optionally fetch product details for checkout page
        $productInfo = $this->cartApiService->getProductForBuyNow($payload['product_id'], $payload['quantity'], $request);
        return response()->json([
            'success' => true,
            'message' => 'Proceed to checkout',
            'data' => $productInfo,
            'status' => true,
            'resolved' => $resolved,
        ]);
    }

     /**
     * Get cart count for a guest user (AJAX endpoint).
     * Route: /api/cart/count?guest_user_id=xxx
     */
    public function cartCount(Request $request): JsonResponse
    {
        $resolved = $this->cartUserResolverService->resolve($request);
        $count = $this->cartApiService->getCartCount($request);
        $response = response()->json([
            'status' => true,
            'message' => 'Data fetch Successfully',
            'count' => $count
        ]);

        return $response;
    }
    /**
     * Update cart item quantity.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateQuantity(Request $request): JsonResponse
    {
        $payload = $request->all();
        $validator = Validator::make($payload, [
            'cart_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()->toArray(),
                'status' => 422,
            ], 422);
        }

        $resolved = $this->cartUserResolverService->resolve($request);
        $result = $this->cartApiService->updateCartQuantity($payload, $request);
        return response()->json($result);
    }

    /**
     * Delete item from cart.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteItem(Request $request): JsonResponse
    {
        $payload = $request->all();
        $validator = Validator::make($payload, [
            'cart_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()->toArray(),
                'status' => 422,
            ], 422);
        }

        $resolved = $this->cartUserResolverService->resolve($request);
        $result = $this->cartApiService->deleteCartItem($payload, $request);
        return response()->json($result);
    }

    /**
     * Get user cart.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCart(Request $request): JsonResponse
    {
        $resolved = $this->cartUserResolverService->resolve($request);
        $result = $this->cartApiService->getCart($request);
        return response()->json($result);
    }
}
