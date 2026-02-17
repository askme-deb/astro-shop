@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
<style>
    .cart-item.is-loading {
        position: relative;
        pointer-events: none;
    }
    .cart-item.is-loading .cart-details, 
    .cart-item.is-loading img {
        visibility: hidden;
    }
    .cart-item.is-loading::after {
        content: "";
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite linear;
        border-radius: 8px;
    }
    @keyframes skeleton-loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
<div class="container cart-page mt-5 mb-5" id="cart-container">

    <h2 class="mb-4">Shopping Cart</h2>

    @if(isset($error))
            <div class="alert alert-danger">{{ $error }}</div>
    @endif

    <div class="cart-layout row g-4">

        <!-- LEFT: Cart items -->
        <div class="col-lg-7">
            @if(isset($cart) && is_array($cart) && count($cart) > 0)
                @php $cartTotal = 0; @endphp

                @foreach($cart as $item)
                    @php
                        $product = $item['product'] ?? [];
                        $unitPrice = isset($product['total_price']) ? (float) $product['total_price'] : (float) ($item['amount'] ?? 0);
                        $quantity = (int) ($item['quantity'] ?? 1);
                        $lineTotal = $unitPrice * $quantity;
                        $cartTotal += $lineTotal;
                    @endphp

                    <div class="cart-item d-flex align-items-start p-3 mb-3 border rounded" data-cart-item-id="{{ $item['id'] }}" data-product-id="{{ $item['product_id'] }}">
                        <img src="{{ !empty($product['image_url']) ? $product['image_url'] : asset('assets/images/product-1.jpg') }}" 
                                 alt="{{ $product['name'] ?? 'Product' }}" 
                                 class="me-3" 
                                 style="width: 80px; height: 80px; object-fit: cover;">

                        <div class="cart-details flex-grow-1">
                            <div class="cart-title d-flex justify-content-between align-items-start">
                                <h4 class="h6 mb-1">{{ $product['name'] ?? 'Unknown Product' }}</h4>
                                <span class="close-btn" style="cursor:pointer" onclick="openPopup('{{ $item['id'] }}')">✕</span>
                            </div>

                            <div class="mb-2">
                                <span class="price fw-bold">₹{{ number_format($unitPrice, 2) }}</span>
                            </div>

                            <div class="qty-box d-inline-flex align-items-center mb-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="changeQty('{{ $item['id'] }}', {{ $item['product_id'] }}, -1)">−</button>
                                <input type="number" class="form-control form-control-sm mx-2 text-center" style="width:70px" value="{{ $quantity }}" min="1" readonly>
                                <button class="btn btn-sm btn-outline-secondary" onclick="changeQty('{{ $item['id'] }}', {{ $item['product_id'] }}, 1)">+</button>
                            </div>

                            <div class="total fw-semibold">
                                Total: ₹<span>{{ number_format($lineTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-muted">Your cart is empty.</p>
                <a href="/products" class="btn btn-primary mt-3" style="background:#f98700;border-color:#f98700;">Start Shopping</a>
            @endif
        </div>

      
        <!-- RIGHT: Order summary & offers -->
        <div class="col-lg-4">
            <div class="cart-footer border rounded p-3">

                <h3 class="h6 mb-3">Order Summary</h3>

                <div class="totals d-flex justify-content-between align-items-center mb-2">
                    <h4 class="h6 mb-0">Estimated total:</h4>
                    <div class="totals_wrapper d-flex align-items-center gap-2">
                        <p class="totals__total-value fw-bold mb-0">₹{{ isset($cartTotal) ? number_format($cartTotal, 2) : '0.00' }}</p>
                        <div class="tooltip">ⓘ
                            <span class="tooltiptext">
                                Price inclusive of tax. Shipping and discounts calculated at checkout.
                            </span>
                        </div>
                    </div>
                </div>

                <p style="margin-top:15px;font-weight:bold">
                    Copy the coupon code below and apply it at checkout!
                </p>

                <div class="accordion mb-2">
                    <div class="accordion__intro">EXTRA 16% OFF above ₹1999</div>
                    <div class="accordion__content offer d-flex justify-content-between align-items-center" data-code="SWEET16">
                        <div class="offer-code">SWEET16</div>
                        <div class="copy-code" onclick="copyCode('SWEET16', this)">Copy Code</div>
                    </div>
                </div>

                <div class="accordion mb-2">
                    <div class="accordion__intro">FLAT 20% OFF above ₹4499</div>
                    <div class="accordion__content offer d-flex justify-content-between align-items-center" data-code="LOVE20">
                        <div class="offer-code">LOVE20</div>
                        <div class="copy-code" onclick="copyCode('LOVE20', this)">Copy Code</div>
                    </div>
                </div>

                <div id="rest-offers" style="display:none">
                    <div class="accordion mb-2">
                        <div class="accordion__intro">FLAT 10% OFF above ₹2499</div>
                        <div class="accordion__content offer d-flex justify-content-between align-items-center" data-code="LOVE10">
                            <div class="offer-code">LOVE10</div>
                            <div class="copy-code" onclick="copyCode('LOVE10', this)">Copy Code</div>
                        </div>
                    </div>
                </div>

                <div id="show-more" class="show-hide-offers mt-2" style="cursor:pointer" onclick="toggleOffers(true)">View more</div>
                <div id="hide-more" class="show-hide-offers mt-2" style="cursor:pointer;display:none" onclick="toggleOffers(false)">Close</div>

                <button class="cart__checkout-button btn btn-dark w-100 mt-3" onclick="window.location.href='{{ route('checkout.index') }}'">
                    Checkout Securely
                </button>

            </div>
        </div>

    </div>
  <!-- POPUP for remove -->
        <div class="popup" id="popup" style="display:none;">
            <div class="popup-content border rounded p-3" style="max-width:360px;margin:0 auto;background:#fff;">
                <div class="popup-header d-flex justify-content-between align-items-center mb-2">
                    <h3 class="h6 mb-0">Remove item from cart?</h3>
                    <span onclick="closePopup()" style="cursor:pointer">✕</span>
                </div>

                <p class="mb-3">Are you sure you want to remove this item?</p>

                <div class="popup-actions d-flex justify-content-end gap-2">
                    <button class="btn btn-sm btn-danger" onclick="confirmRemoveItem()">Remove</button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="closePopup()">Cancel</button>
                </div>
            </div>
        </div>

</div>

@push('scripts')
<script>
    let cartItemIdToRemove = null;

    function setLoadingState(loading) {
        const container = document.getElementById('cart-container');
        if(loading) {
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';
        } else {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        }
    }

    function changeQty(cartItemId, productId, delta) {
        const itemEl = document.querySelector('.cart-item[data-cart-item-id="' + cartItemId + '"]');
        if (!itemEl) return;

        const qtyInput = itemEl.querySelector('input[type="number"]');
        let currentQty = parseInt(qtyInput.value || '1', 10);
        const newQty = currentQty + delta;
        if (newQty < 1) return;

        setLoadingState(true);
        
        const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : '';

        // User changed controller to expect 'cart_id' instead of 'cart_item_id'
        fetch('/api/cart/update-quantity', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'include',
            body: JSON.stringify({ cart_id: cartItemId, product_id: productId, quantity: newQty })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.status) {
                window.location.reload();
            } else {
                alert(data.message || data.error || 'Failed to update cart');
                setLoadingState(false);
            }
        })
        .catch(err => {
            console.error(err);
            setLoadingState(false);
        });
    }

    function openPopup(cartItemId) {
        cartItemIdToRemove = cartItemId;
        document.getElementById('popup').style.display = 'block';
    }

    function closePopup() {
        cartItemIdToRemove = null;
        document.getElementById('popup').style.display = 'none';
    }

    function confirmRemoveItem() {
        if (!cartItemIdToRemove) return;
        setLoadingState(true);
        const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : '';

        fetch('/api/cart/delete-item', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'include',
            body: JSON.stringify({ cart_id: cartItemIdToRemove })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.status) {
                window.location.reload();
            } else {
                alert(data.message || data.error || 'Failed to remove item');
                setLoadingState(false);
            }
        })
        .catch(err => {
            console.error(err);
            setLoadingState(false);
        })
        .finally(() => {
            closePopup();
        });
    }

    function copyCode(code, el) {
        navigator.clipboard.writeText(code).then(() => {
            el.textContent = 'Copied';
            setTimeout(() => {
                el.textContent = 'Copy Code';
            }, 1500);
        });
    }

    function toggleOffers(show) {
        const rest = document.getElementById('rest-offers');
        const showBtn = document.getElementById('show-more');
        const hideBtn = document.getElementById('hide-more');
        if (show) {
            rest.style.display = 'block';
            showBtn.style.display = 'none';
            hideBtn.style.display = 'block';
        } else {
            rest.style.display = 'none';
            showBtn.style.display = 'block';
            hideBtn.style.display = 'none';
        }
    }
</script>
@endpush
@endsection