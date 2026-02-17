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

    public function index(Request $request)
    {
        return view('carts.index');
    }
}
