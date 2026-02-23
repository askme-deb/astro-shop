<?php

namespace App\Services\Api\Payment;

use App\Services\Api\Clients\BaseApiClient;

class PaymentApiService extends BaseApiClient
{
    public function createRazorpayOrder($payload)
    {
        return $this->request('POST', 'checkout/payment/create-razorpay-order', ['json' => $payload]);
    }

    public function verifyRazorpayPayment($payload)
    {
        return $this->request('POST', 'checkout/payment/verify', ['json' => $payload]);
    }
}
