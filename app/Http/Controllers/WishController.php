<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Api\WishlistApiService;

class WishController extends Controller
{
    protected $wishlistApiService;

    public function __construct(WishlistApiService $wishlistApiService)
    {
        $this->wishlistApiService = $wishlistApiService;
    }

    public function index(Request $request)
    {
        // You may want to fetch the wishlist items here
        $wishlist = $this->wishlistApiService->getWishlistItems($request);
        return view('wishlist.index', compact('wishlist'));
    }
        public function remove(Request $request)
    {
        $wishlistId = $request->input('wishlist_id');
        if (!$wishlistId) {
            return response()->json(['success' => false, 'message' => 'Wishlist ID is required.'], 400);
        }
        $result = $this->wishlistApiService->removeWishlistItem($wishlistId, $request);
        // Optionally, you can refresh the wishlist and return it
        return response()->json($result);
    }
}
