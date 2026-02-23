<?php

namespace App\Services\Api\Checkout;

use App\Services\Api\Clients\BaseApiClient;

class CheckoutApiService extends BaseApiClient
{
    public function fetchCheckoutDetails($payload)
    {
        return $this->request('POST', 'checkout/details', ['json' => $payload]);
    }
}
