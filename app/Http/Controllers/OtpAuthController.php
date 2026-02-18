<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RequestOtpRequest;
use App\Http\Requests\Auth\ResendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Services\Api\Contracts\AuthApiServiceInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class OtpAuthController extends Controller
{
    public function __construct(protected AuthApiServiceInterface $authApi)
    {
    }

    public function showLoginForm(Request $request): View
    {
        return view('auth.otp-login');
    }

    public function requestOtp(RequestOtpRequest $request): JsonResponse
    {
        $context = $request->string('context', 'header')->toString();

        $response = $this->authApi->requestOtp(
            $request->string('mobile_no')->toString(),
            $request->string('country_code')->toString()
        );

        if (! $response->success) {
            return response()->json([
                'success' => false,
                'message' => $response->message ?? 'Unable to send OTP. Please try again.',
                'context' => $context,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $response->message ?? 'OTP sent successfully.',
            'context' => $context,
        ]);
    }

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        $context = $request->string('context', 'header')->toString();

        $response = $this->authApi->resendOtp(
            $request->string('mobile_no')->toString(),
            $request->string('country_code')->toString()
        );

        if (! $response->success) {
            return response()->json([
                'success' => false,
                'message' => $response->message ?? 'Unable to resend OTP. Please try again later.',
                'context' => $context,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $response->message ?? 'OTP resent successfully.',
            'context' => $context,
        ]);
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $context = $request->string('context', 'header')->toString();

        $response = $this->authApi->verifyOtp(
            $request->string('mobile_no')->toString(),
            $request->string('country_code')->toString(),
            $request->string('otp')->toString()
        );

        if (! $response->success) {
            return response()->json([
                'success' => false,
                'message' => $response->message ?? 'The OTP you entered is invalid or expired.',
                'context' => $context,
            ], 422);
        }

        $userPayload = $response->data['user'] ?? $response->data['customer'] ?? $response->data;
        $token = $response->data['token'] ?? $response->data['access_token'] ?? null;

        if ($token === null) {
            Log::warning('OTP verification succeeded but no token returned from API.', [
                'context' => $context,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Authentication service did not return a session token.',
                'context' => $context,
            ], 500);
        }

        Session::regenerate();
        Session::put('auth.api_token', $token);
        Session::put('auth.user', [
            'id' => $userPayload['id'] ?? null,
            'name' => $userPayload['name'] ?? null,
            'mobile_no' => $userPayload['mobile_no'] ?? $request->string('mobile_no')->toString(),
            'email' => $userPayload['email'] ?? null,
        ]);

        $redirectUrl = $context === 'checkout'
            ? route('checkout.index')
            : (url()->previous() ?: route('products.index'));

        return response()->json([
            'success' => true,
            'message' => $response->message ?? 'Logged in successfully.',
            'redirect_url' => $redirectUrl,
            'context' => $context,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'You have been logged out.',
        ]);
    }
}
