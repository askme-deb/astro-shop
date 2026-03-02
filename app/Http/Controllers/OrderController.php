<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the orders.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $orders = collect();
        $error = null;
        $token = (string) $request->session()->get('auth.api_token', '');
        try {
            $orders = $this->orderService->getOrders($request->all(), $token);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch orders', ['error' => $e->getMessage()]);
            $error = 'Unable to fetch orders at this time. Please try again later.';
        }
        return view('orders.index', compact('orders', 'error'));
    }



        /**
     * Display the specified order details.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|int $order
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $order)
    {
        $error = null;
        $orderDetails = null;
        $token = (string) $request->session()->get('auth.api_token', '');
        try {
            $orderDetails = $this->orderService->getOrderDetails($order, $token);
            $orderTimeline = null;
            if ($orderDetails && isset($orderDetails['order_number'])) {
                $orderTimeline = $this->orderService->getOrderTimeline($orderDetails['order_number'], $token);
            }
            if (!$orderDetails) {
                $error = 'Order not found or could not be loaded.';
            }
            //dd($orderDetails, $orderTimeline);
        } catch (\Throwable $e) {
            \Log::error('Failed to fetch order details', ['error' => $e->getMessage()]);
            $error = 'Unable to fetch order details at this time. Please try again later.';
            $orderTimeline = null;
        }
        return view('orders.details', compact('orderDetails', 'orderTimeline', 'error'));
    }
}
