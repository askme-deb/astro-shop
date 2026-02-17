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
        $result = $this->cartApiService->buyNow($payload, $request);
        $resolved = $this->cartUserResolverService->resolve($request);
        $response = response()->json($result);

        return $response;
    }

     /**
     * Get cart count for a guest user (AJAX endpoint).
     * Route: /api/cart/count?guest_user_id=xxx
     */
    public function cartCount(Request $request): JsonResponse
    {
        $count = $this->cartApiService->getCartCount($request);
        $resolved = $this->cartUserResolverService->resolve($request);
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
           // 'product_id' => 'required|integer', // The API seems to use product_id based on existing addToCart, but user asked for cart_item_id. I will support both or check what index.blade sends.
            // Wait, the user requirement said "Validate inputs properly (cart_item_id required, quantity numeric/min 1)."
            // But the blade template uses product_id.
            // Let's look at the user request again: "Validate inputs properly (cart_item_id required...)"
            // However, the existing blade template uses `product_id`.
            // The external API documentation says "POST /api/v1/update/cart/quantity".
            // I'll stick to what the blade needs, which is likely `product_id` for now, OR I will update blade to use `cart_item_id` if available.
            // Looking at `index.blade.php`, it currently uses `product_id`.
            // The `cart` array has `product_id`.
            // Let's assume the external API expects `cart_item_id` based on the user prompt, OR maybe `product_id`.
            // The user said: "Validate inputs properly (cart_item_id required...)"
            // I should check if `cart` items have `id` (which would be `cart_item_id`).
            // In the JSON dump: `id`: 382, `product_id`: 4.
            // So `id` is the cart item id.
            // I need to update Blade to pass `cart_item_id` (which is `id` from the API response) instead of `product_id` for updates if the API requires `cart_item_id`.
            // But for now, I will add the controller method to accept `cart_item_id`.
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

        $result = $this->cartApiService->updateCartQuantity($payload);
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

        $result = $this->cartApiService->deleteCartItem($payload);
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
        $result = $this->cartApiService->getCart($request);
        return response()->json($result);
    }
}
