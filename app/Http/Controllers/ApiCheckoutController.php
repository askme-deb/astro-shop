<?php

namespace App\Http\Controllers;

use App\Services\Api\Checkout\CheckoutApiService;
use App\Services\Api\Order\OrderApiService;
use App\Services\Api\Payment\PaymentApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ApiCheckoutController extends Controller
{
    protected $checkoutApi;
    protected $orderApi;
    protected $paymentApi;

    public function __construct(
        CheckoutApiService $checkoutApi,
        OrderApiService $orderApi,
        PaymentApiService $paymentApi
    ) {
        $this->checkoutApi = $checkoutApi;
        $this->orderApi = $orderApi;
        $this->paymentApi = $paymentApi;
    }

    public function fetchCheckoutDetails(Request $request)
    {
        $payload = $request->only([
            'user_id', 'guest_user_id', 'cart_items', 'coupon_code', 'shipping_address_id'
        ]);
        try {
            $result = $this->checkoutApi->fetchCheckoutDetails($payload);
            return response()->json($result);
        } catch (\Throwable $e) {
            Log::error('Checkout details API error', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Unable to fetch checkout details.'], 502);
        }
    }

    public function placeOrder(Request $request)
    {
        $payload = $request->only([
            'user_id', 'guest_user_id', 'address_id', 'payment_method', 'coupon_code', 'order_notes', 'cart_items'
        ]);
        try {
            $result = $this->orderApi->placeOrder($payload);
            return response()->json($result);
        } catch (\Throwable $e) {
            Log::error('Place order API error', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Unable to place order.'], 502);
        }
    }

    public function createRazorpayOrder(Request $request)
    {
        $payload = $request->only(['order_id', 'amount', 'currency']);
        try {
            $result = $this->paymentApi->createRazorpayOrder($payload);
            return response()->json($result);
        } catch (\Throwable $e) {
            Log::error('Razorpay order API error', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Unable to create Razorpay order.'], 502);
        }
    }

    public function verifyRazorpayPayment(Request $request)
    {
        $payload = $request->only([
            'razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature', 'order_id',
            'coupon_code', 'coupon_discount', 'price_gst', 'discounted_price'
        ]);
        try {
            // Try to get token from Authorization header or cookie
          //  $token = $request->bearerToken();
          //  if (!$token) {
                $token = $request->cookie('auth_api_token');
           // }
            $result = $this->paymentApi->verifyRazorpayPayment($payload, $token);
            return response()->json($result);
        } catch (\Throwable $e) {
            Log::error('Razorpay verify API error', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Unable to verify payment.'], 502);
        }
    }
}
