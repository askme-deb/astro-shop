<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Razorpay Webhook Route
Route::post('/razorpay/webhook', function (Request $request) {
    $payload = $request->getContent();
    $signature = $request->header('X-Razorpay-Signature');
    $webhookSecret = config('services.razorpay.webhook_secret');

    // Verify signature
    $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);
    if (!hash_equals($expectedSignature, $signature)) {
        Log::warning('Razorpay webhook signature mismatch', [
            'expected' => $expectedSignature,
            'received' => $signature,
        ]);
        return response()->json(['status' => false, 'message' => 'Invalid signature'], 400);
    }

    $data = json_decode($payload, true);
    Log::info('Razorpay webhook received', $data);

    // Handle event types (payment.captured, order.paid, etc.)
    if (isset($data['event'])) {
        $client = new \GuzzleHttp\Client([
            'base_uri' => config('services.api.base_url'),
            'timeout'  => 10.0,
        ]);
        $apiToken = config('services.api.token');
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $apiToken,
        ];

        switch ($data['event']) {
            case 'payment.captured':
            case 'order.paid':
                // Forward payment status to external API
                try {
                    $client->post('/api/v1/payment/webhook', [
                        'headers' => $headers,
                        'json' => $data,
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to notify external API for Razorpay webhook', [
                        'event' => $data['event'],
                        'error' => $e->getMessage(),
                    ]);
                }
                break;
            // Add more cases as needed
        }
    }

    return response()->json(['status' => true]);
});
