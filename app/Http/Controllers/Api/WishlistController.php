<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\WishlistApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    protected WishlistApiService $wishlistApiService;

    public function __construct(WishlistApiService $wishlistApiService)
    {
        $this->wishlistApiService = $wishlistApiService;
    }

    /**
     * Toggle wishlist for a given product.
     */
    public function toggle(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'min:1'],
        ]);

        $result = $this->wishlistApiService->toggleWishlist((int) $data['product_id'], $request);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'in_wishlist' => $result['in_wishlist'],
        ], $result['success'] ? 200 : 422);
    }

    /**
     * Check if a product is in the wishlist for the current user/guest.
     */
    public function check(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'min:1'],
        ]);

        $inWishlist = $this->wishlistApiService->checkWishlist((int) $data['product_id'], $request);

        return response()->json([
            'status' => $inWishlist !== null,
            'in_wishlist' => $inWishlist,
        ]);
    }

    /**
     * Get wishlist count for current user/guest.
     */
    public function count(Request $request): JsonResponse
    {
        $count = $this->wishlistApiService->getWishlistCount($request);

        return response()->json([
            'status' => true,
            'count' => $count,
        ]);
    }
}
