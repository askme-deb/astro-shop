<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use App\Services\Api\CartApiService;
use App\Services\Api\CartUserResolverService;
use Illuminate\Http\Request;

class MergeGuestCartAfterLogin
{
    protected CartApiService $cartApiService;
    protected CartUserResolverService $cartUserResolverService;
    protected Request $request;

    public function __construct(CartApiService $cartApiService, CartUserResolverService $cartUserResolverService, Request $request)
    {
        $this->cartApiService = $cartApiService;
        $this->cartUserResolverService = $cartUserResolverService;
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $guestId = $this->request->cookie($this->cartUserResolverService->getCookieName());
        $userId = $event->user->getAuthIdentifier();
        if ($guestId && $userId) {
            // Fetch guest cart
            $guestCart = $this->cartApiService->getGuestCart($guestId);
            // Switch to user context
            $userRequest = $this->request->duplicate();
            $userRequest->setUserResolver(function () use ($event) {
                return $event->user;
            });
            $userCart = $this->cartApiService->getCart($userRequest);
            // Merge logic: combine quantities, prevent duplicates
            $mergedItems = [];
            $productMap = [];
            if (isset($userCart['data']['items'])) {
                foreach ($userCart['data']['items'] as $item) {
                    $productMap[$item['product_id']] = $item;
                }
            }
            if (isset($guestCart['data']['items'])) {
                foreach ($guestCart['data']['items'] as $item) {
                    if (isset($productMap[$item['product_id']])) {
                        // Combine quantities
                        $productMap[$item['product_id']]['quantity'] += $item['quantity'];
                    } else {
                        $productMap[$item['product_id']] = $item;
                    }
                }
            }
            $mergedItems = array_values($productMap);
            // Update user cart with merged items
            foreach ($mergedItems as $item) {
                $this->cartApiService->addToCart([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ], $userRequest);
            }
            // Remove guest cart cookie
            Cookie::queue($this->cartUserResolverService->forgetGuestCookie());
            Log::info('Merged guest cart into user cart after login', ['user_id' => $userId]);
        }
    }
}
