<?php

namespace App\Http\Controllers;

use App\Services\Api\CartApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     *
     * @param Request $request
     * @param CartApiService $cartApiService
     * @return View|RedirectResponse
     */
    public function index(Request $request, CartApiService $cartApiService)
    {
        // Get guest_user_id from cookie or generate if missing
        $guestUserId = $request->cookie('guest_user_id');
        if (!$guestUserId) {
            $guestUserId = uniqid('guest_', true);
            Cookie::queue('guest_user_id', $guestUserId, 60 * 24 * 30);
        }

        // Validate guest_user_id format (basic example)
        if (!preg_match('/^guest_[a-zA-Z0-9._-]+$/', $guestUserId)) {
            return redirect()->route('home')->withErrors('Invalid guest user ID.');
        }

        try {
           // dd($guestUserId);
            $response = $cartApiService->getCart($guestUserId);
           // dd($response['data']);
            if ($response['status'] === 'success') {
                $cart = $response['data'];
                return view('carts.index', compact('cart'));
            } else {
                Log::error('Cart API error: ' . $response['message']);
                return view('carts.index', ['cart' => null, 'error' => $response['message']]);
            }
        } catch (\Throwable $e) {
            Log::error('Cart API Exception: ' . $e->getMessage(), ['exception' => $e]);
            return view('cart.index', ['cart' => null, 'error' => 'Unable to load cart at this time. Please try again later.']);
        }
    }
}
