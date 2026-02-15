<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page.
     */
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        $total = 0;
        foreach ($cart as $item) {
            $price = $item['price'] ?? 0;
            $qty = $item['qty'] ?? 1;
            $total += $price * $qty;
        }

        return view('checkout.index', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    /**
     * Handle checkout form submission.
     */
    public function store(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'pincode' => 'required|string|max:20',
            'payment_method' => 'required|string|in:cod,online',
        ]);

        $total = 0;
        foreach ($cart as $item) {
            $price = $item['price'] ?? 0;
            $qty = $item['qty'] ?? 1;
            $total += $price * $qty;
        }

        $orderId = 'ORD-' . strtoupper(uniqid());

        $request->session()->put('last_order', [
            'id' => $orderId,
            'total' => $total,
            'items' => $cart,
            'customer' => $data,
        ]);

        $request->session()->forget('cart');

        return redirect()
            ->route('checkout.index')
            ->with('success', 'Order placed successfully. Your order ID is ' . $orderId . '.');
    }
}
