<?php

namespace App\Services\Api\Payment;

use App\Services\Api\Clients\BaseApiClient;

class PaymentApiService extends BaseApiClient
{
    public function createRazorpayOrder($payload)
    {
        return $this->request('POST', 'checkout/payment/create-razorpay-order', ['json' => $payload]);
    }

    public function verifyRazorpayPayment($payload, $token = null)
    {
        $options = ['json' => $payload];
        if ($token) {
            $options['headers'] = [
                'Authorization' => 'Bearer ' . $token,
            ];
        }
        return $this->request('POST', 'checkout/payment/verify', $options);
    }
}
