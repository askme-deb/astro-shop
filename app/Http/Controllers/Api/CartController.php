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
        $resolved = $this->cartUserResolverService->resolve($request);
        $result = $this->cartApiService->buyNow($payload, $request);
        $response = response()->json($result);

        return $response;
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
