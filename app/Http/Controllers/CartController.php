<?php

namespace App\Http\Controllers;

use App\Services\Api\CartApiService;
use App\Services\Api\CouponApiService;
use App\Services\Api\CartUserResolverService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Apply a coupon code to the current cart via external API.
     *
     * Authentication is token-based (API login), not Laravel auth.
     * We resolve the identifier from the API session or guest cookie.
     */
    public function applyCoupon(Request $request, CouponApiService $couponApiService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => ['required', 'string', 'max:100'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Please provide a valid coupon code.',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        // Prefer API user identifier from session; fall back to guest ID.
        $guestUserId = $request->session()->get('api_user_id')
            ?? $request->session()->get('guest_user_id')
            ?? $request->cookie('guest_user_id');

        if (! $guestUserId) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Unable to resolve user. Please refresh the page and try again.',
            ], 400);
        }

        try {
            $apiResponse = $couponApiService->applyCoupon([
                'guest_user_id' => $guestUserId,
                'coupon_code' => $request->input('coupon_code'),
            ]);

            $success = (bool) ($apiResponse['status'] ?? $apiResponse['success'] ?? false);
            $httpStatus = $success ? 200 : 422;
            $message = (string) ($apiResponse['message'] ?? (
                $success ? 'Coupon applied successfully.' : 'Unable to apply coupon.'
            ));

            return response()->json([
                'success' => $success,
                'status' => $success,
                'message' => $message,
                // Expose normalized data payload; if API already wraps in data, keep it.
                'data' => $apiResponse['data'] ?? $apiResponse,
            ], $httpStatus);
        } catch (\Throwable $exception) {
            Log::error('Apply coupon failed', [
                'service' => static::class,
                'message' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Something went wrong while applying the coupon. Please try again.',
            ], 500);
        }
    }
}
