<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\CartApiService;
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

    public function __construct(CartApiService $cartApiService)
    {
        $this->cartApiService = $cartApiService;
    }

    public function addToCart(Request $request): JsonResponse
    {
        $payload = $request->all();
        // Add guest_user_id to payload if not present, using cookie
        if (!isset($payload['guest_user_id'])) {
            $guestUserId = $request->cookie('guest_user_id', uniqid('guest_'));
            $payload['guest_user_id'] = $guestUserId;
        } else {
            $guestUserId = $payload['guest_user_id'];
        }

        $validator = Validator::make($payload, [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'guest_user_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()->toArray(),
                'status' => 422,
            ], 422)->cookie('guest_user_id', $guestUserId, 60 * 24 * 30); // 30 days
        }

        $result = $this->cartApiService->addToCart($payload);
       //dd($result);
        return response()->json($result)->cookie('guest_user_id', $guestUserId, 60 * 24 * 30); // 30 days
    }

    public function buyNow(Request $request): JsonResponse
    {
        $payload = $request->all();
        // Add guest_user_id to payload if not present, using cookie
        if (!isset($payload['guest_user_id'])) {
            $guestUserId = $request->cookie('guest_user_id', uniqid('guest_'));
            $payload['guest_user_id'] = $guestUserId;
        } else {
            $guestUserId = $payload['guest_user_id'];
        }

        $validator = Validator::make($payload, [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'guest_user_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $validator->errors()->toArray(),
                'status' => 422,
            ], 422)->cookie('guest_user_id', $guestUserId, 60 * 24 * 30); // 30 days
        }

        $result = $this->cartApiService->buyNow($payload);
        return response()->json($result)->cookie('guest_user_id', $guestUserId, 60 * 24 * 30); // 30 days
    }

     /**
     * Get cart count for a guest user (AJAX endpoint).
     * Route: /api/cart/count?guest_user_id=xxx
     */
    public function cartCount(Request $request): JsonResponse
    {
        $guestUserId = $request->query('guest_user_id');
        if (!$guestUserId) {
            $guestUserId = $request->cookie('guest_user_id');
        }
        if (!$guestUserId) {
            $guestUserId = uniqid('guest_', true);
        }
        $count = $this->cartApiService->getCartCount($guestUserId);
        return response()->json([
            'status' => true,
            'message' => 'Data fetch Successfully',
            'count' => $count
        ])->cookie('guest_user_id', $guestUserId, 60 * 24 * 30); // 30 days
    }
}
