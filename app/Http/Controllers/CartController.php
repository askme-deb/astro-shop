<?php

namespace App\Http\Controllers;

use App\Services\Api\CartApiService;
use App\Services\Api\CartUserResolverService;
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
    protected CartUserResolverService $cartUserResolverService;

    public function __construct(CartUserResolverService $cartUserResolverService)
    {
        $this->cartUserResolverService = $cartUserResolverService;
    }

    public function index(Request $request, CartApiService $cartApiService)
    {
        // Use CartUserResolverService to get user/guest ID

        try {
            $response = $cartApiService->getCart($request);
          
            if ($response['status'] === 'success') {
                $cart = $response['data'];

                return view('carts.index', compact('cart'));
            } else {
                Log::error('Cart API error: ' . $response['message']);
                return view('carts.index', ['cart' => null, 'error' => $response['message']]);
            }
        } catch (\Throwable $e) {
            Log::error('Cart API Exception: ' . $e->getMessage(), ['exception' => $e]);
            return view('carts.index', ['cart' => null, 'error' => 'Unable to load cart at this time. Please try again later.']);
        }
    }
}
