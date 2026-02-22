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
                <!-- COUPON -->
                <div class="coupon-row">
                    <div class="coupon-input-wrapper" style="display:flex;align-items:center;gap:8px;width:100%;flex-wrap:wrap;margin-bottom:12px;">
                        <input type="text" id="cart-coupon" placeholder="Discount code" style="flex:1;min-width:0;">
                        <button id="cart-apply-coupon-btn" type="button">Apply</button>
                    </div>
                    <span id="cart-applied-coupon-chip" style="display:none;align-items:center;background:#f1f3f6;border-radius:16px;padding:2px 10px 2px 8px;font-size:0.95em;color:#333;margin-top:4px;margin-bottom:8px;">
                        <span id="cart-applied-coupon-code" style="font-weight:500;"></span>
                        <button id="cart-remove-coupon-chip-btn" type="button" style="background:none;border:none;color:#888;font-size:1.1em;cursor:pointer;margin-left:0px;line-height:1;padding:5px;">&#10005;</button>
                    </span>
                </div>
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

@push('scripts')
<script>
// Coupon UI logic for cart page (matches checkout)
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('cart-coupon');
    const chip = document.getElementById('cart-applied-coupon-chip');
    const chipCode = document.getElementById('cart-applied-coupon-code');
    const applyBtn = document.getElementById('cart-apply-coupon-btn');
    const removeBtn = document.getElementById('cart-remove-coupon-chip-btn');

    function updateCouponUI() {
        if (codeInput.value) {
            chipCode.textContent = codeInput.value;
            chip.style.display = 'flex';
            codeInput.style.display = 'none';
            applyBtn.style.display = 'none';
        } else {
            chip.style.display = 'none';
            codeInput.style.display = '';
            applyBtn.style.display = '';
        }
    }

    if (codeInput && chip && chipCode && applyBtn && removeBtn) {
        updateCouponUI();
        // Only show chip after apply
        applyBtn.addEventListener('click', async function() {
            const rawCode = (codeInput.value || '').trim();
            if (!rawCode) {
                toast('Please enter a coupon code.', true);
                return;
            }
            // Simulate API call (replace with real endpoint)
            applyBtn.disabled = true;
            try {
                // Example: let response = await fetch('/api/cart/apply-coupon', ...)
                // For now, just show success
                setTimeout(function() {
                    toast('Coupon applied successfully.', false);
                    updateCouponUI();
                    applyBtn.disabled = false;
                }, 500);
            } catch (e) {
                toast('Network error while applying coupon. Please try again.', true);
                applyBtn.disabled = false;
            }
        });
        removeBtn.addEventListener('click', async function() {
            chipCode.textContent = '';
            chip.style.opacity = '0.6';
            // Simulate API call (replace with real endpoint)
            try {
                // Example: let response = await fetch('/api/cart/remove-coupon', ...)
                // For now, just show success
                setTimeout(function() {
                    codeInput.value = '';
                    chip.style.display = 'none';
                    chip.style.opacity = '';
                    toast('Coupon removed.', false);
                    updateCouponUI();
                }, 500);
            } catch (e) {
                toast('Network error while removing coupon. Please try again.', true);
                chip.style.opacity = '';
            }
        });
    }
});
</script>
@endpush
