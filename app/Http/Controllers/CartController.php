<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Show the current cart.
     */
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        return view('cart.index', [
            'cart' => $cart,
        ]);
    }

    /**
     * Add an item to the cart.
     */
    public function add(Request $request)
    {
        $data = $request->validate([
            'slug' => 'required|string',
            'name' => 'nullable|string',
            'price' => 'nullable|numeric',
            'qty' => 'nullable|integer|min:1',
        ]);

        $slug = $data['slug'];
        $qty = $data['qty'] ?? 1;

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$slug])) {
            $cart[$slug]['qty'] += $qty;
        } else {
            $cart[$slug] = [
                'slug' => $slug,
                'name' => $data['name'] ?? $slug,
                'price' => $data['price'] ?? 0,
                'qty' => $qty,
            ];
        }

        $request->session()->put('cart', $cart);

        return back();
    }

    /**
     * Remove a single item from the cart.
     */
    public function remove(Request $request, string $slug)
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$slug])) {
            unset($cart[$slug]);
            $request->session()->put('cart', $cart);
        }

        return back();
    }

    /**
     * Clear the entire cart.
     */
    public function clear(Request $request)
    {
        $request->session()->forget('cart');

        return back();
    }
}
