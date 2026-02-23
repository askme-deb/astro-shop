@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
    <div class="container cart-page mt-5 mb-5" id="cart-container">

        <h2 class="mb-4">Shopping Cart</h2>

        <div id="cart-error-container"></div>

        <div class="cart-layout">

            <!-- LEFT: dynamic cart items -->
            <div class="container" id="cart-items-container">
                <div class="skeleton-container">
                    <div class="skeleton-item-box">
                        <div class="skeleton-img-box"></div>
                        <div class="skeleton-content-box">
                            <div class="skeleton-title-box"></div>
                            <div class="skeleton-price-box"></div>
                            <div class="skeleton-qty-box"></div>
                            <div class="skeleton-total-box"></div>
                        </div>
                    </div>
                    <div class="skeleton-item-box">
                        <div class="skeleton-img-box"></div>
                        <div class="skeleton-content-box">
                            <div class="skeleton-title-box"></div>
                            <div class="skeleton-price-box"></div>
                            <div class="skeleton-qty-box"></div>
                            <div class="skeleton-total-box"></div>
                        </div>
                    </div>
                    <div class="skeleton-item-box">
                        <div class="skeleton-img-box"></div>
                        <div class="skeleton-content-box">
                            <div class="skeleton-title-box"></div>
                            <div class="skeleton-price-box"></div>
                            <div class="skeleton-qty-box"></div>
                            <div class="skeleton-total-box"></div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT: Order summary & offers -->
            <div class="cart-footer" id="cart-summary-container">

                <h3>Order Summary</h3>

                <div class="totals">
                    <h4>Estimated total:</h4>
                    <div class="totals_wrapper">
                        {{-- <p class="total__total-compare-value">₹5,799.00</p> --}}
                        <p class="totals__total-value" id="estimated-total">₹0.00</p>
                        <div class="tooltip">ⓘ
                            <span class="tooltiptext">
                                Price inclusive of tax. Shipping and discounts calculated at checkout.
                            </span>
                        </div>
                    </div>
                </div>

                <div id="coupon-summary" style="display:none; margin-top:8px; font-size:0.9rem;">
                    <div style="display:flex; justify-content:space-between;">
                        <span>Coupon discount:</span>
                        <span id="coupon-discount-amount">-₹0.00</span>
                    </div>
                </div>
                <p style="margin-top:15px;font-weight:normal; color:#E9718B; font-size:1.08em; display:flex; align-items:center; gap:7px;">
                    {{-- <span style="font-size:1.3em;">🎁</span> --}}
                    <span>Have a coupon? Copy the code below and apply it at checkout for instant savings!</span>
                </p>

                <!-- OFFERS -->
                <div id="cart-dynamic-coupons"></div>

                {{-- <div id="show-more" class="show-hide-offers" style="cursor:pointer" onclick="toggleOffers(true)">View more
                </div> --}}
                <div id="hide-more" class="show-hide-offers" style="cursor:pointer;display:none"
                    onclick="toggleOffers(false)">Close</div>
                <!-- Coupon input -->
                {{-- <div class="coupon-section mt-3">
                    <div class="coupon-row">
                        <div class="coupon-input-wrapper" style="display:flex;align-items:center;gap:8px;width:100%;flex-wrap:wrap;margin-bottom:12px;">
                            <input type="text" id="coupon_code" placeholder="Discount code" style="flex:1;min-width:0;">
                            <button id="apply-coupon-btn" type="button">Apply</button>
                        </div>
                        <span id="applied-coupon-chip" style="display:none;align-items:center;background:#f1f3f6;border-radius:16px;padding:2px 10px 2px 8px;font-size:0.95em;color:#333;margin-top:4px;margin-bottom:8px;">
                            <span id="applied-coupon-code" style="font-weight:500;"></span>
                            <button id="remove-coupon-chip-btn" type="button" style="background:none;border:none;color:#888;font-size:1.1em;cursor:pointer;margin-left:0px;line-height:1;padding:5px;">&#10005;</button>
                        </span>
                    </div>
                    <div id="checkout-coupon-message" class="coupon-message" style="margin-top:4px;font-size:0.85rem;"></div>
                </div> --}}

                <!-- Gift Wrap -->
                {{-- <div class="all_gift_wrap">
                    <input type="checkbox" id="gift">
                    <label for="gift">
                        <strong style="color:#E9718B">Gift wrap</strong> all items (+₹50 per item)
                    </label>
                </div> --}}

                <button class="cart__checkout-button" onclick="checkoutWithLoading()">
                    Checkout Securely
                </button>
    <script>
        function checkoutWithLoading() {
            setLoadingState(true);
            setTimeout(function() {
                window.location.href = '{{ route('checkout.index') }}';
            }, 200);
        }
    </script>

            </div>
            <!-- POPUP -->
            <div class="popup" id="popup">
                <div class="popup-content">
                    <div class="popup-header">
                        <h3>Move from cart?</h3>
                        <span onclick="closePopup()" style="cursor:pointer">✕</span>
                    </div>

                    <p>Move this item to your wishlist and buy later.</p>

                    <div class="popup-actions">
                        <a onclick="confirmRemoveItem()">Remove</a>
                        <a onclick="closePopup()">Cancel</a>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('styles')
    <style>
        .skeleton-placeholder {
            display: none;
        }

        .skeleton-item-box {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            display: flex;
            gap: 16px;
        }

        .skeleton-img-box {
            width: 80px;
            height: 80px;
            background: #eee;
            border-radius: 4px;
            animation: skeleton-shimmer 1.5s infinite linear;
        }

        .skeleton-content-box {
            flex-grow: 1;
        }

        .skeleton-title-box {
            height: 18px;
            background: #eee;
            width: 60%;
            margin-bottom: 12px;
            border-radius: 2px;
            animation: skeleton-shimmer 1.5s infinite linear;
        }

        .skeleton-price-box {
            height: 16px;
            background: #eee;
            width: 30%;
            margin-bottom: 12px;
            border-radius: 2px;
            animation: skeleton-shimmer 1.5s infinite linear;
        }

        .skeleton-qty-box {
            height: 32px;
            background: #eee;
            width: 120px;
            margin-bottom: 12px;
            border-radius: 4px;
            animation: skeleton-shimmer 1.5s infinite linear;
        }

        .skeleton-total-box {
            height: 16px;
            background: #eee;
            width: 40%;
            border-radius: 2px;
            animation: skeleton-shimmer 1.5s infinite linear;
        }

        @keyframes skeleton-shimmer {
            0% {
                background-color: #f0f0f0;
            }

            50% {
                background-color: #e0e0e0;
            }

            100% {
                background-color: #f0f0f0;
            }
        }

        .coupon-row {
            margin-top: 20px;
        }

        .coupon-input-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .coupon-input-wrapper input[type="text"] {
            flex: 1;
            min-width: 0;
        }

        #applied-coupon-chip {
            display: none;
            align-items: center;
            background: #f1f3f6;
            border-radius: 16px;
            padding: 2px 10px 2px 8px;
            font-size: 0.95em;
            color: #333;
            margin-top: 4px;
            margin-bottom: 8px;
        }

        #applied-coupon-code {
            font-weight: 500;
        }

        #remove-coupon-chip-btn {
            background: none;
            border: none;
            color: #888;
            font-size: 1.1em;
            cursor: pointer;
            margin-left: 0px;
            line-height: 1;
            padding: 5px;
        }

        .coupon-message {
            font-size: 0.85rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let cartItemIdToRemove = null;

        function setLoadingState(loading, cartItemId = null) {
            if (cartItemId) {
                const itemEl = document.querySelector(`.cart-item[data-cart-item-id="${cartItemId}"]`);
                if (!itemEl) return;

                if (loading) {
                    itemEl.dataset.originalHtml = itemEl.innerHTML;
                    itemEl.innerHTML = `
                    <div class="skeleton-img-box" style="width: 80px; height: 80px; margin-right: 15px;"></div>
                    <div class="skeleton-content-box flex-grow-1">
                        <div class="skeleton-title-box" style="width: 60%;"></div>
                        <div class="skeleton-price-box" style="width: 30%;"></div>
                        <div class="skeleton-qty-box" style="width: 100px;"></div>
                        <div class="skeleton-total-box" style="width: 40%;"></div>
                    </div>
                `;
                    itemEl.style.pointerEvents = 'none';
                    itemEl.style.border = '1px solid #eee';
                } else {
                    if (itemEl.dataset.originalHtml) {
                        itemEl.innerHTML = itemEl.dataset.originalHtml;
                        delete itemEl.dataset.originalHtml;
                    }
                    itemEl.style.pointerEvents = 'auto';
                    itemEl.style.border = '';
                }
            } else {
                const container = document.getElementById('cart-container');
                if (loading) {
                    container.style.opacity = '0.5';
                    container.style.pointerEvents = 'none';
                } else {
                    container.style.opacity = '1';
                    container.style.pointerEvents = 'auto';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchAndRenderCart();

            const applyBtn = document.getElementById('apply-coupon-btn');
            if (applyBtn) {
                applyBtn.addEventListener('click', applyCoupon);
            }
        });

        async function fetchAndRenderCart() {
            const itemsContainer = document.getElementById('cart-items-container');
            const summaryContainer = document.getElementById('cart-summary-container');
            const errorContainer = document.getElementById('cart-error-container');

            try {
                const response = await fetch('/api/cart', {
                    credentials: 'include'
                });
                const data = await response.json();

                if (data.status === 'success') {
                    renderCart(data.data);
                    if (summaryContainer) summaryContainer.style.display = 'block';
                } else {
                    throw new Error(data.message || 'Failed to fetch cart');
                }
            } catch (err) {
                console.error(err);
                if (errorContainer) {
                    errorContainer.innerHTML = `<div class="alert alert-danger">${err.message}</div>`;
                }
                if (itemsContainer) {
                    itemsContainer.innerHTML = '<p class="text-muted">Your cart is empty.</p>';
                }
                if (summaryContainer) summaryContainer.style.display = 'none';
            }
        }

        function renderCart(items) {
            const itemsContainer = document.getElementById('cart-items-container');
            const summaryContainer = document.getElementById('cart-summary-container');
            let cartTotal = 0;

            if (!items || items.length === 0) {
                if (itemsContainer) {
                    itemsContainer.innerHTML = `
                    <p class="text-muted">Your cart is empty.</p>
                    <a href="/products" class="btn btn-primary mt-3" style="background:#f98700;border-color:#f98700;">Start Shopping</a>
                `;
                }
                if (summaryContainer) summaryContainer.style.display = 'none';
                return;
            }

            let html = '';
            items.forEach(item => {
                const product = item.product || {};
                const unitPrice = parseFloat(product.total_price || item.amount || 0);
                const quantity = parseInt(item.quantity || 1);
                const lineTotal = unitPrice * quantity;
                cartTotal += lineTotal;

                const imageUrl = product.image_url ? product.image_url : '/assets/images/product-1.jpg';
                const comparePrice = product.compare_at_price ?
                    `₹${parseFloat(product.compare_at_price).toLocaleString('en-IN', { minimumFractionDigits: 2 })}` :
                    '';

                html += `
                <div class="cart-item" data-cart-item-id="${item.id}" data-product-id="${item.product_id}">
                    <img src="${imageUrl}" alt="${product.name || 'Product'}">

                    <div class="cart-details">
                      <div class="cart-title">
                        <h4>${product.name || 'Unknown Product'}</h4>
                        <span class="close-btn" style="cursor:pointer" onclick="openPopup('${item.id}')">✕</span>
                      </div>

                      <div>
                        <span class="price">₹${unitPrice.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</span>
                        ${comparePrice ? `<span class="compare">${comparePrice}</span>` : ''}
                      </div>

                      <div class="qty-box">
                        <button onclick="changeQty('${item.id}', ${item.product_id}, -1)">−</button>
                        <input type="number" value="${quantity}" min="1" readonly>
                        <button onclick="changeQty('${item.id}', ${item.product_id}, 1)">+</button>
                      </div>

                      <div class="gift-wrap">
                        <input type="checkbox">
                        <label> Add gift wrap (+ ₹50)</label>
                      </div>

                      <div class="total">
                        Total: ₹<span>${lineTotal.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</span>
                      </div>
                    </div>
                </div>
            `;
            });

            if (itemsContainer) {
                itemsContainer.innerHTML = html;
            }

            const totalEl = document.getElementById('estimated-total');
            if (totalEl) {
                totalEl.innerText = '₹' + cartTotal.toLocaleString('en-IN', {
                    minimumFractionDigits: 2
                });
            }
        }

        function changeQty(cartItemId, productId, delta) {
            const itemEl = document.querySelector('.cart-item[data-cart-item-id="' + cartItemId + '"]');
            if (!itemEl) return;

            const qtyInput = itemEl.querySelector('input[type="number"]');
            let currentQty = parseInt(qtyInput.value || '1', 10);
            const newQty = currentQty + delta;
            if (newQty < 1) return;

            setLoadingState(true, cartItemId);

            const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : '';

            // Determine if user is logged in
            let payload = {
                cart_id: cartItemId,
                product_id: productId,
                quantity: newQty
            };
            let isLoggedIn = !!document.querySelector('form#header-logout-form');
            if (!isLoggedIn) {
                // Guest: add guest_user_id from localStorage or cookie
                let guestUserId = localStorage.getItem('guest_user_id') || '';
                if (!guestUserId) {
                    // Try to get from cookie
                    const match = document.cookie.match(/guest_user_id=([^;]+)/);
                    if (match) guestUserId = match[1];
                }
                if (guestUserId) payload.guest_user_id = guestUserId;
            }

            fetch('/api/cart/update-quantity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'include',
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success || data.status) {
                        fetchAndRenderCart();
                        if (typeof updateCartCount === 'function') updateCartCount();
                    } else {
                        alert(data.message || data.error || 'Failed to update cart');
                        setLoadingState(false, cartItemId);
                    }
                })
                .catch(err => {
                    console.error(err);
                    setLoadingState(false, cartItemId);
                });
        }

        function openPopup(cartItemId) {
            cartItemIdToRemove = cartItemId;
            const popup = document.getElementById('popup');
            if (popup) popup.style.display = 'block';
        }

        function closePopup() {
            cartItemIdToRemove = null;
            const popup = document.getElementById('popup');
            if (popup) popup.style.display = 'none';
        }

        function confirmRemoveItem() {
            if (!cartItemIdToRemove) return;
            const targetId = cartItemIdToRemove;
            setLoadingState(true, targetId);

            const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : '';

            // Determine if user is logged in
            let payload = {
                cart_id: targetId
            };
            let isLoggedIn = !!document.querySelector('form#header-logout-form');
            if (!isLoggedIn) {
                // Guest: add guest_user_id from localStorage or cookie
                let guestUserId = localStorage.getItem('guest_user_id') || '';
                if (!guestUserId) {
                    // Try to get from cookie
                    const match = document.cookie.match(/guest_user_id=([^;]+)/);
                    if (match) guestUserId = match[1];
                }
                if (guestUserId) payload.guest_user_id = guestUserId;
            }
            fetch('/api/cart/delete-item', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'include',
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success || data.status) {
                        fetchAndRenderCart();
                        if (typeof updateCartCount === 'function') updateCartCount();
                    } else {
                        alert(data.message || data.error || 'Failed to remove item');
                        setLoadingState(false, targetId);
                    }
                })
                .catch(err => {
                    console.error(err);
                    setLoadingState(false, targetId);
                })
                .finally(() => {
                    closePopup();
                });
        }

        function copyCode(code, el) {
            navigator.clipboard.writeText(code).then(() => {
                const parent = el.parentElement;
                if (!parent) return;
                const copiedEl = parent.querySelector('.copied');
                if (copiedEl) {
                    el.style.display = 'none';
                    copiedEl.style.display = 'block';
                    setTimeout(() => {
                        copiedEl.style.display = '';
                        el.style.display = '';
                    }, 1500);
                }
            });
        }

        function toggleOffers(show) {
            const rest = document.getElementById('rest-offers');
            const showBtn = document.getElementById('show-more');
            const hideBtn = document.getElementById('hide-more');
            if (!rest || !showBtn || !hideBtn) return;

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

        // Modern coupon UX for cart page
        document.addEventListener('DOMContentLoaded', function() {
            const codeInput = document.getElementById('coupon_code');
            const chip = document.getElementById('applied-coupon-chip');
            const chipCode = document.getElementById('applied-coupon-code');
            const applyBtn = document.getElementById('apply-coupon-btn');
            const removeBtn = document.getElementById('remove-coupon-chip-btn');

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
                applyBtn.addEventListener('click', async function() {
                    const rawCode = codeInput.value.trim();
                    if (!rawCode) {
                        toast('Please enter a coupon code.', true);
                        return;
                    }
                    const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : '';
                    applyBtn.disabled = true;
                    const originalText = applyBtn.textContent;
                    applyBtn.textContent = 'Applying...';
                    setLoadingState(true);
                    try {
                        const response = await fetch("{{ route('apply.coupon') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            credentials: 'include',
                            body: JSON.stringify({
                                coupon_code: rawCode
                            }),
                        });
                        const data = await response.json();
                        const ok = (data && (data.success || data.status)) && response.ok;
                        if (!ok) {
                            const errorMessage = data && (data.message || data.error) ?
                                (data.message || data.error) :
                                'Unable to apply coupon.';
                            toast(errorMessage, true);
                            return;
                        }
                        const payload = (data && data.data && typeof data.data === 'object') ? data
                            .data : data;
                        const discountRaw = payload.discount_amount ?? payload.discount ?? 0;
                        const grandTotalRaw = payload.grand_total ?? payload.total ?? payload
                            .payable_amount ?? null;
                        const couponSummaryEl = document.getElementById('coupon-summary');
                        const discountEl = document.getElementById('coupon-discount-amount');
                        const totalEl = document.getElementById('estimated-total');
                        if (couponSummaryEl && discountEl && typeof discountRaw === 'number') {
                            couponSummaryEl.style.display = discountRaw > 0 ? 'block' : 'none';
                            discountEl.textContent = '-₹' + discountRaw.toLocaleString('en-IN', {
                                minimumFractionDigits: 2
                            });
                        }
                        if (totalEl && grandTotalRaw !== null && !isNaN(grandTotalRaw)) {
                            const grand = Number(grandTotalRaw);
                            totalEl.textContent = '₹' + grand.toLocaleString('en-IN', {
                                minimumFractionDigits: 2
                            });
                        }
                        toast(data.message || 'Coupon applied successfully.', false);
                        updateCouponUI();
                    } catch (error) {
                        toast('Network error while applying coupon. Please try again.', true);
                    } finally {
                        applyBtn.disabled = false;
                        applyBtn.textContent = originalText;
                        setLoadingState(false);
                    }
                });
                removeBtn.addEventListener('click', async function() {
                    chipCode.textContent = '';
                    chip.style.opacity = '0.6';
                    const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : '';
                    try {
                        const response = await fetch('/remove-coupon', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            credentials: 'include',
                            body: JSON.stringify({
                                coupon_code: codeInput.value.trim()
                            }),
                        });
                        const data = await response.json();
                        const ok = (data && (data.success || data.status)) && response.ok;
                        if (!ok) {
                            const errorMessage = data && (data.message || data.error) ?
                                (data.message || data.error) :
                                'Unable to remove coupon.';
                            toast(errorMessage, true);
                            chip.style.opacity = '';
                            return;
                        }
                        codeInput.value = '';
                        chip.style.display = 'none';
                        chip.style.opacity = '';
                        toast(data.message || 'Coupon removed.', false);
                        updateCouponUI();
                        // Optionally reset totals/discounts here
                        const couponSummaryEl = document.getElementById('coupon-summary');
                        const discountEl = document.getElementById('coupon-discount-amount');
                        const totalEl = document.getElementById('estimated-total');
                        if (couponSummaryEl && discountEl) {
                            couponSummaryEl.style.display = 'none';
                            discountEl.textContent = '-₹0.00';
                        }
                        if (totalEl) {
                            // Optionally reset total, or re-fetch cart
                        }
                    } catch (error) {
                        toast('Network error while removing coupon. Please try again.', true);
                        chip.style.opacity = '';
                    }
                });
            }
        });

        // --- Dynamic coupon rendering (improved logic) ---
        document.addEventListener('DOMContentLoaded', function() {
            const dynamicCouponsContainer = document.getElementById('cart-dynamic-coupons');

            function formatCurrency(amount) {
                return Number(amount).toLocaleString('en-IN', {
                    minimumFractionDigits: 0
                });
            }

            function bindOfferCopyButtons() {
                if (!dynamicCouponsContainer) return;
                const copyBtns = dynamicCouponsContainer.querySelectorAll('.copy-code');
                copyBtns.forEach(function(btn) {
                    btn.onclick = function() {
                        const code = btn.parentElement.querySelector('.offer-code').textContent.trim();
                        navigator.clipboard.writeText(code).then(() => {
                            btn.style.display = 'none';
                            const copiedEl = btn.parentElement.querySelector('.copied');
                            if (copiedEl) {
                                copiedEl.style.display = 'block';
                                setTimeout(() => {
                                    copiedEl.style.display = '';
                                    btn.style.display = '';
                                }, 1500);
                            }
                        });
                    };
                });
            }

            function renderCoupons(coupons) {
                if (!dynamicCouponsContainer) {
                    return;
                }

                if (!Array.isArray(coupons) || coupons.length === 0) {
                    dynamicCouponsContainer.innerHTML = '';
                    return;
                }

                let html = '';

                coupons.forEach(function(coupon) {
                    if (!coupon || typeof coupon !== 'object') {
                        return;
                    }

                    const rawCode = coupon.code || coupon.coupon_code || '';
                    const code = String(rawCode).trim();
                    if (!code) {
                        return;
                    }

                    const title = coupon.title || coupon.name || code;
                    const minOrder = coupon.min_order_value || coupon.min_order || null;
                    const usageLimit = coupon.usage_limit || coupon.max_uses || null;

                    let intro = title;
                    if (minOrder) {
                        intro += ' (Min ₹' + formatCurrency(minOrder) + ')';
                    }

                    let subtitleParts = [];
                    if (coupon.description) {
                        subtitleParts.push(String(coupon.description));
                    }
                    if (usageLimit) {
                        subtitleParts.push('Usage limit: ' + usageLimit);
                    }

                    html += `
                <div class="accordion">
                    <div class="accordion__intro">${intro}</div>
                    <div class="accordion__content offer" data-code="${code}">
                        <div class="offer-code">${code}</div>
                        <div class="copy-code">Copy Code</div>
                        <div class="copied">Copied</div>
                    </div>
                </div>`;
                });

                dynamicCouponsContainer.innerHTML = html;

                // Re-bind copy handlers for all offers, including the new ones.
                bindOfferCopyButtons();
            }

            function fetchCoupons() {
                if (!dynamicCouponsContainer) {
                    return;
                }

                fetch('/api/coupons', {
                        credentials: 'include',
                    })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            return {
                                response: response,
                                data: data,
                            };
                        }).catch(function() {
                            return {
                                response: response,
                                data: {
                                    status: false,
                                    message: 'Unexpected coupon server response.',
                                    coupons: [],
                                },
                            };
                        });
                    })
                    .then(function(result) {
                        if (!result.response.ok || !result.data || !result.data.status) {
                            return;
                        }

                        renderCoupons(result.data.coupons || []);
                    })
                    .catch(function() {
                        // Silently ignore coupon errors; checkout can proceed without them.
                    });
            }

            fetchCoupons();
        });
        // --- End dynamic coupon rendering ---
    </script>
@endpush
