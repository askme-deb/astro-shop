<?php

namespace App\Services;

use App\Services\Api\Order\OrderApiService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    protected OrderApiService $orderApiService;

    public function __construct(OrderApiService $orderApiService)
    {
        $this->orderApiService = $orderApiService;
    }

    /**
     * Fetch single order details from external API.
     *
     * @param int|string $id
     * @param string|null $token
     * @return array|null
     */
    public function getOrderDetails($id, string $token = null)
    {
        try {
            $response = $this->orderApiService->getOrderDetails($id, $token);
            if (isset($response['error']) || !isset($response['data'])) {
                Log::error('Order API error', ['response' => $response]);
                return null;
            }
            return $response['data'];
        } catch (\Throwable $e) {
            Log::error('OrderService getOrderDetails exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Fetch orders from external API.
     *
     * @param array $params
     * @return Collection|LengthAwarePaginator
     */
    public function getOrders(array $params = [], string $token = null)
    {
        try {
            $response = $this->orderApiService->getOrders($params, $token);
            if (isset($response['error']) || !isset($response['data'])) {
                Log::error('Order API error', ['response' => $response]);
                return collect();
            }
            // Handle paginated structure: data['data']
            $ordersData = $response['data']['data'] ?? $response['data'];
            $orders = collect($ordersData);
            // Pagination support if API provides meta or pagination keys
            if (isset($response['data']['total'])) {
                return new LengthAwarePaginator(
                    $orders,
                    $response['data']['total'] ?? $orders->count(),
                    $response['data']['per_page'] ?? $orders->count(),
                    $response['data']['current_page'] ?? 1,
                    ['path' => request()->url(), 'query' => request()->query()]
                );
            }
            return $orders;
        } catch (\Throwable $e) {
            Log::error('OrderService getOrders exception', ['error' => $e->getMessage()]);
            return collect();
        }
    }

        /**
     * Fetch order timeline from external API.
     *
     * @param string $orderNumber
     * @param string|null $token
     * @return array|null
     */
    public function getOrderTimeline(string $orderNumber, string $token = null)
    {
        try {
            $response = $this->orderApiService->getOrderTimeline($orderNumber, $token);
            if (isset($response['error']) || !isset($response['data'])) {
                Log::error('Order API timeline error', ['response' => $response]);
                return null;
            }
            return $response['data'];
        } catch (\Throwable $e) {
            Log::error('OrderService getOrderTimeline exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
