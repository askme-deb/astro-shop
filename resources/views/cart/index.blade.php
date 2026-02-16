@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
    <div class="container cart-page mt-5 mb-5">

        <h2 class="mb-4">Shopping Cart</h2>


        {{-- Cart Error Message --}}
        @if (!empty($error))
            <div class="alert alert-danger">{{ $error }}</div>
        @endif
        @php
            //dd($cart);
        @endphp
        {{-- Cart Items --}}
        @if (!empty($cart) && is_array($cart) && count($cart))
            <div class="container">
                @php $total = 0; @endphp
                @foreach ($cart as $item)
                    @php
                        $product = $item['product'] ?? [];
                        $price = isset($product['total_price']) ? (float)$product['total_price'] : (isset($product['price']) ? (float)$product['price'] : 0);
                        $qty = (int)($item['quantity'] ?? 0);
                        $subtotal = $price * $qty;
                        $total += $subtotal;
                    @endphp
                    <div class="cart-item" id="cartItem">
                        <img src="{{ e($product['image'] ?? '') }}" alt="{{ e($product['name'] ?? '') }}" width="80">
                        <div class="cart-details">
                            <div class="cart-title">
                                <h4>{{ e($product['name'] ?? '') }}</h4>
                                <span class="close-btn" onclick="openPopup()">✕</span>
                            </div>
                            <div>
                                <span class="price">₹{{ number_format($price, 2) }}</span>
                            </div>
                            <div class="qty-box">
                                <span>Qty: {{ e($qty) }}</span>
                            </div>
                            <div class="total">
                                Subtotal: ₹{{ number_format($subtotal, 2) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">Your cart is empty.</div>
        @endif

        <!-- POPUP -->
        <div class="popup" id="popup">
            <div class="popup-content">
                <div class="popup-header">
                    <h3>Move from cart?</h3>
                    <span onclick="closePopup()" style="cursor:pointer">✕</span>
                </div>

                <p>Move this item to your wishlist and buy later.</p>

                <div class="popup-actions">
                    <a onclick="removeItem()">Remove</a>
                    <a onclick="closePopup()">Cancel</a>
                </div>
            </div>
        </div>






        @if (!empty($cart) && is_array($cart) && count($cart))
            <!-- RIGHT -->
            <div class="cart-footer">
                <h3>Order Summary</h3>
                <div class="totals">
                    <h4>Estimated total:</h4>
                    <div class="totals_wrapper">
                        <p class="totals__total-value">₹{{ number_format($total ?? 0, 2) }}</p>
                        <div class="tooltip">ⓘ
                            <span class="tooltiptext">
                                Price inclusive of tax. Shipping and discounts calculated at checkout.
                            </span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('checkout.index') }}" class="cart__checkout-button">
                    Checkout Securely
                </a>
            </div>
        @endif
    </div>

    </div>

@endsection
