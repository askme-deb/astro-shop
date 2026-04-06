<?php

namespace App\Services\Api\Order;

use App\Services\Api\Clients\BaseApiClient;
use Illuminate\Support\Facades\Log;

class OrderApiService extends BaseApiClient
{
    protected function normalizePlaceOrderPayload(array $payload, bool $hasToken): array
    {
        if ($hasToken) {
            unset($payload['user_id'], $payload['guest_user_id']);
        } else {
            unset($payload['user_id']);
        }

        if (! empty($payload['cart_items']) && is_array($payload['cart_items'])) {
            $payload['cart_items'] = array_values(array_map(function ($item) {
                $product = is_array($item['product'] ?? null) ? $item['product'] : [];

                return array_filter([
                    'product_id' => $item['product_id'] ?? $product['id'] ?? null,
                    'quantity' => isset($item['quantity']) ? (int) $item['quantity'] : 1,
                    'amount' => isset($item['amount']) ? (float) $item['amount'] : (isset($product['price']) ? (float) $product['price'] : null),
                    'carat' => $item['carat'] ?? $product['carat'] ?? null,
                    'variation_name' => $item['variation_name'] ?? null,
                    'variation_price' => isset($item['variation_price']) ? (float) $item['variation_price'] : null,
                    'product_variation_options_id' => $item['product_variation_options_id'] ?? null,
                ], static function ($value) {
                    return $value !== null && $value !== '';
                });
            }, $payload['cart_items']));
        }

        return $payload;
    }

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

    public function placeOrder($payload, string $token = null)
    {
        $hasToken = $token !== null && $token !== '';
        $payload = $this->normalizePlaceOrderPayload((array) $payload, $hasToken);

        $options = [
            'json' => $payload,
        ];

        if ($hasToken) {
            $options['headers'] = [
                'Authorization' => 'Bearer ' . $token,
            ];
        }

        $response = $this->request('POST', 'checkout/place-order', $options);
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
