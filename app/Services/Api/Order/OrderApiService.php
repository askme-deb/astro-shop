<?php

namespace App\Services\Api\Order;

use App\Services\Api\Clients\BaseApiClient;

class OrderApiService extends BaseApiClient
{
    public function placeOrder($payload)
    {
        $response = $this->request('POST', 'checkout/place-order', ['json' => $payload]);
        // If the response contains an error, log it and return it
        if (isset($response['error']) || isset($response['message'])) {
            \Log::error('Astro API placeOrder error', [
                'payload' => $payload,
                'response' => $response,
            ]);
        }
        return $response;
    }
}
