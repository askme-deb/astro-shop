<?php

namespace App\Services\Api\Order;

use App\Services\Api\Clients\BaseApiClient;
use Illuminate\Support\Facades\Log;

class OrderApiService extends BaseApiClient
{
    /**
     * Fetch order timeline from external API.
     *
     * @param string $orderNumber
     * @param string|null $token
     * @return array
     */
    public function getOrderTimeline(string $orderNumber, string $token = null): array
    {
        try {
            $headers = [];
            if ($token) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }
            $response = $this->request('GET', 'orders/track/' . $orderNumber, [
                'headers' => $headers,
            ]);
            return $response;
        } catch (\Throwable $e) {
            Log::error('Astro API getOrderTimeline error', [
                'order_number' => $orderNumber,
                'error' => $e->getMessage(),
            ]);
            return [
                'error' => true,
                'message' => 'Failed to fetch order timeline',
            ];
        }
    }
    /**
     * Fetch single order details from external API.
     *
     * @param int|string $id
     * @param string|null $token
     * @return array
     */
    public function getOrderDetails($id, string $token = null): array
    {
        try {
            $headers = [];
            if ($token) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }
            $response = $this->request('GET', 'orders/' . $id, [
                'headers' => $headers,
            ]);
            return $response;
        } catch (\Throwable $e) {
            Log::error('Astro API getOrderDetails error', [
                'order_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return [
                'error' => true,
                'message' => 'Failed to fetch order details',
            ];
        }
    }

    /**
     * Fetch orders from external API.
     *
     * @param array $params
     * @return array
     */
    public function getOrders(array $params = [], string $token = null): array
    {
        try {
            $headers = [];
            if ($token) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }
            $response = $this->request('GET', 'orders', [
                'query' => $params,
                'headers' => $headers,
            ]);
            return $response;
        } catch (\Throwable $e) {
            Log::error('Astro API getOrders error', [
                'params' => $params,
                'error' => $e->getMessage(),
            ]);
            return [
                'error' => true,
                'message' => 'Failed to fetch orders',
            ];
        }
    }

    public function placeOrder($payload)
    {
        $response = $this->request('POST', 'checkout/place-order', ['json' => $payload]);
        // If the response contains an error, log it and return it
        if (isset($response['error']) || isset($response['message'])) {
            Log::error('Astro API placeOrder error', [
                'payload' => $payload,
                'response' => $response,
            ]);
        }
        return $response;
    }
}
