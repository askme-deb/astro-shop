@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="checkout-wrapper">

        <!-- LEFT -->
        <div class="checkout-left">
            <h2 class="checkout-title">Checkout</h2>

            <!-- STEP 1: LOGIN / CUSTOMER (MOBILE + OTP) -->
            <section class="checkout-card checkout-step">
                <div class="checkout-step-header">
                    <span class="step-number">1</span>
                    <h3 class="step-title">
                        {{ session()->has('auth.api_token') ? 'CUSTOMER DETAILS' : 'LOGIN / CUSTOMER DETAILS' }}
                    </h3>
                </div>
                <div class="checkout-step-body">

                    <!-- LOGGED-IN SUMMARY (shown when API-authenticated session exists) -->
                    <div id="step-logged-in" class="checkout-step-pane"
                        style="display: {{ session()->has('auth.api_token') ? 'block' : 'none' }};">
                        <div class="logged-in-summary" id="customer-summary">
                            @php($authUser = session('auth.user'))
                            <div class="logged-in-name">
                                {{ trim(($authUser['first_name'] ?? '') . ' ' . ($authUser['last_name'] ?? '')) ?: 'Guest' }}
                            </div>
                            <div class="logged-in-contact">
                                <span>{{ $authUser['email'] ?? 'No email on file' }}</span>
                                <span> {{ $authUser['mobile_no'] ?? '' ? '+91-' . $authUser['mobile_no'] : '' }}</span>
                            </div>
                            <div class="logged-in-note">You are logged in. Customer details are pre-filled.</div>
                        </div>
                    </div>

                    <!-- STEP 1A: ENTER MOBILE (for guests, AJAX OTP request) -->
                    <div id="step-mobile" class="checkout-step-pane"
                        style="display: {{ session()->has('auth.api_token') ? 'none' : 'block' }};">
                        <div id="checkout-otp-alert" class="logged-in-note" style="display:none;"></div>
                        <div class="form-row">
                            <label for="checkout-mobile-input">Mobile Number</label>
                            <input type="tel" id="checkout-mobile-input" placeholder="Enter your WhatsApp  number"
                                autocomplete="tel" inputmode="numeric">
                        </div>
                        <div class="customer-edit-actions">
                            <button type="button" class="btn-deliver" id="btn-checkout-send-otp">Send OTP</button>
                        </div>
                        <p class="logged-in-note">
                            We’ll send an OTP to this number and then log you in securely.
                        </p>
                    </div>

                    <!-- STEP 1B: VERIFY OTP (AJAX OTP verification) -->
                    <div id="step-login" class="checkout-step-pane" style="display:none;">
                        <div class="logged-in-note" id="checkout-otp-instructions">
                            We found an account with this mobile number. Please enter the OTP sent to your phone.
                        </div>
                        <div class="form-row">
                            <label>Mobile Number</label>
                            <input type="tel" id="checkout-login-mobile" readonly>
                        </div>
                        <div class="form-row">
                            <label for="checkout-login-otp-1">OTP Code</label>
                            <div class="otp-input-group" id="checkout-otp-group">
                                <input type="text" id="checkout-login-otp-1" class="otp-input" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                                <input type="text" id="checkout-login-otp-2" class="otp-input" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                                <input type="text" id="checkout-login-otp-3" class="otp-input" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                                <input type="text" id="checkout-login-otp-4" class="otp-input" maxlength="1" inputmode="numeric" autocomplete="one-time-code">
                            </div>
                            <input type="hidden" id="checkout-login-otp">
                        </div>
                        <div class="customer-edit-actions">
                            <button type="button" class="btn-deliver" id="btn-checkout-verify-otp">Verify OTP &amp;
                                Login</button>
                            <button type="button" class="btn-link" id="btn-checkout-change-mobile">Change Mobile</button>
                        </div>
                        <p class="logged-in-note">
                            Didn’t receive the OTP?
                            <span class="btn-link" id="btn-checkout-resend-otp" style="padding-left:0;">Resend OTP</span>
                            <span id="checkout-resend-timer" class="logged-in-note"
                                style="display:none; margin-left:6px;"></span>
                        </p>
                    </div>

                </div>
            </section>

            <!-- STEP 2: DELIVERY ADDRESS -->
            <section
                class="checkout-card checkout-step {{ session()->has('auth.api_token') ? '' : 'checkout-step-disabled' }}">
                <div class="checkout-step-header">
                    <span class="step-number">2</span>
                    <h3 class="step-title">DELIVERY ADDRESS</h3>
                </div>
                <div class="checkout-step-body collapsed">
                    <div class="address-list">
                        <div id="addressListSkeleton" class="address-skeleton-list">
                            <div class="address-item skeleton-card">
                                <div class="address-header">
                                    <span class="skeleton-circle"></span>
                                    <span class="skeleton-line skeleton-line--short"></span>
                                    <span class="skeleton-line skeleton-line--tag"></span>
                                </div>
                                <div class="address-body">
                                    <p class="skeleton-line"></p>
                                    <p class="skeleton-line skeleton-line--medium"></p>
                                    <p class="skeleton-line skeleton-line--short"></p>
                                </div>
                            </div>
                            <div class="address-item skeleton-card">
                                <div class="address-header">
                                    <span class="skeleton-circle"></span>
                                    <span class="skeleton-line skeleton-line--short"></span>
                                    <span class="skeleton-line skeleton-line--tag"></span>
                                </div>
                                <div class="address-body">
                                    <p class="skeleton-line"></p>
                                    <p class="skeleton-line skeleton-line--medium"></p>
                                    <p class="skeleton-line skeleton-line--short"></p>
                                </div>
                            </div>
                        </div>

                        <div id="addressError" class="logged-in-note" style="display:none;"></div>

                        <div id="addressListContainer" class="address-list-container" style="display:none;"></div>

                        <div id="addressEmptyState" class="logged-in-note" style="display:none;">
                            No saved addresses found. Add a new address.
                        </div>

                        <input type="hidden" id="selectedAddressId" name="selected_address_id" value="">

                        <!-- Add new address CTA -->
                        <div class="address-add-new" onclick="showAddAddressForm()">
                            + Add a new address
                        </div>

                        <!-- Add new address form (hidden by default) -->
                        <div class="address-add-form" id="address-add-form" style="display:none;">
                            <div id="addressAddError" class="logged-in-note" style="display:none;"></div>
                            <div class="form-row form-row-half">
                                <div>
                                    <label>First Name</label>
                                    <input type="text" id="addFirstName" placeholder="First name">
                                </div>
                                <div>
                                    <label>Last Name</label>
                                    <input type="text" id="addLastName" placeholder="Last name" required>
                                </div>
                            </div>
                            <div class="form-row form-row-half">
                                <div>
                                    <label>Mobile Number</label>
                                    <input type="tel" id="addPhone" placeholder="10-digit mobile number">
                                </div>
                                <div>
                                    <label>Pincode</label>
                                    <input type="text" id="addPincode" placeholder="Pincode">
                                </div>
                            </div>
                            <div class="form-row">
                                <label>Address (House no, Building, Area)</label>
                                <input type="text" id="addAddress"
                                    placeholder="Flat / House no., Building, Street, Area">
                            </div>
                            <div class="form-row form-row-half">
                                <div>
                                    <label>State</label>
                                    <select id="addState">
                                        <option value="">Select State</option>
                                    </select>
                                </div>

                                <div>
                                    <label>City / District</label>
                                    <select id="addCity" disabled>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <label>Landmark (Optional)</label>
                                <input type="text" id="addLandmark" placeholder="Nearby landmark">
                            </div>
                            <div class="form-row address-type-row">
                                <label>Address Type</label>
                                <div class="address-type-options">
                                    <label>
                                        <input type="radio" name="address_type" value="home" checked>
                                        <span class="radio-custom"></span>
                                        Home
                                    </label>
                                    <label>
                                        <input type="radio" name="address_type" value="office">
                                        <span class="radio-custom"></span>
                                        Office
                                    </label>
                                    <label>
                                        <input type="radio" name="address_type" value="others">
                                        <span class="radio-custom"></span>
                                        Other
                                    </label>
                                </div>
                            </div>
                            <div class="address-add-actions">
                                <button type="button" class="btn-save-address" id="addressAddSaveBtn">SAVE AND DELIVER
                                    HERE</button>
                                <button type="button" class="btn-link" onclick="hideAddAddressForm()">CANCEL</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Address Remove Confirmation Popup -->
            <div class="popup" id="addressRemovePopup" style="display:none;">
                <div class="popup-content">
                    <div class="popup-header">
                        <h3>Remove address?</h3>
                        <button type="button" class="popup-close" id="addressRemoveCloseBtn">✕</button>
                    </div>

                    <p class="popup-text">Are you sure you want to remove this address?</p>

                    <div class="popup-actions">
                        <button type="button" class="btn-remove-confirm" id="addressRemoveConfirmBtn">Remove</button>
                        <button type="button" class="btn-remove-cancel" id="addressRemoveCancelBtn">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Address Edit Modal -->
            <div id="addressEditModal" class="address-modal" style="display:none;">
                <div class="address-modal__backdrop"></div>
                <div class="address-modal__content">
                    <div class="address-modal__header">
                        <h4>Edit Address</h4>
                        <button type="button" class="address-modal__close" id="addressEditCloseBtn">&times;</button>
                    </div>
                    <div class="address-modal__body">
                        <div id="addressEditError" class="logged-in-note" style="display:none;"></div>
                        <form id="addressEditForm">
                            <input type="hidden" id="editAddressId" name="address_id">
                            <input type="hidden" id="editCountry" name="country">
                            <div class="form-row form-row-half">
                                <div>
                                    <label>First Name</label>
                                    <input type="text" id="editFirstName" name="first_name" required>
                                </div>
                                <div>
                                    <label>Last Name</label>
                                    <input type="text" id="editLastName" name="last_name" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <label>Email</label>
                                <input type="email" id="editEmail" name="email" required>
                            </div>
                            <div class="form-row form-row-half">
                                <div>
                                    <label>Mobile Number</label>
                                    <input type="tel" id="editPhone" name="phone" required>
                                </div>
                                <div>
                                    <label>Pincode</label>
                                    <input type="text" id="editPincode" name="pincode" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <label>Address</label>
                                <input type="text" id="editAddress" name="address" required>
                            </div>
                            <div class="form-row">
                                <label>Landmark (Optional)</label>
                                <input type="text" id="editLandmark" name="landmark" placeholder="Nearby landmark">
                            </div>
                            <div class="form-row address-type-row">
                                <label>Address Type</label>
                                <div class="address-type-options">
                                    <label>
                                        <input type="radio" name="edit_address_type" value="home" checked>
                                        <span class="radio-custom"></span>
                                        Home
                                    </label>
                                    <label>
                                        <input type="radio" name="edit_address_type" value="office">
                                        <span class="radio-custom"></span>
                                        Office
                                    </label>
                                    <label>
                                        <input type="radio" name="edit_address_type" value="others">
                                        <span class="radio-custom"></span>
                                        Other
                                    </label>
                                </div>
                            </div>
                            <div class="form-row form-row-half">
                                <div>
                                    <label>State</label>
                                    <select id="editState" name="state" required>
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <div>
                                    <label>City</label>
                                    <select id="editCity" name="city" required disabled>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="address-modal__footer">
                        <button type="button" class="btn-link" id="addressEditCancelBtn">Cancel</button>
                        <button type="button" class="btn-save-address" id="addressEditSaveBtn">Save Changes</button>
                    </div>
                </div>
            </div>

            <!-- STEP 3: PAYMENT OPTIONS -->
            <section
                class="checkout-card checkout-step {{ session()->has('auth.api_token') ? '' : 'checkout-step-disabled' }}">
                <div class="checkout-step-header">
                    <span class="step-number">3</span>
                    <h3 class="step-title">PAYMENT OPTIONS</h3>
                </div>
                <div class="checkout-step-body collapsed">
                    <div class="payment-methods">
                        <label class="payment-option payment-option-active">
                            <div class="payment-option-main">
                                <input type="radio" name="payment" checked>
                                <span class="radio-custom"></span>
                                <div class="payment-option-text">
                                    <span class="payment-title">Online Payment</span>
                                    <span class="payment-subtitle">Pay via UPI, Cards, NetBanking and Wallets using
                                        Razorpay.</span>
                                </div>
                            </div>
                        </label>

                        <label class="payment-option">
                            <div class="payment-option-main">
                                <input type="radio" name="payment">
                                <span class="radio-custom"></span>
                                <div class="payment-option-text">
                                    <span class="payment-title">Cash on Delivery</span>
                                    <span class="payment-subtitle">Pay in cash or card when your order is delivered.</span>
                                </div>
                            </div>
                        </label>
                    </div>

                    <p class="payment-note">Payments are processed securely via Razorpay. You will be redirected to a
                        secure payment page to complete your transaction.</p>
                </div>
            </section>
        </div>

        <!-- RIGHT -->
        <div class="checkout-right">

            <div class="summary-box">

                <h3>Order Summary</h3>
                <div id="checkout-summary-items" class="summary-items">
                    <p class="logged-in-note">Loading your cart...</p>
                </div>

                <!-- Dynamic coupons from external API will be rendered here -->
                <div id="checkout-dynamic-coupons"></div>

                <!-- COUPON -->
                <div class="coupon-row">
                    <div class="coupon-input-wrapper" style="display:flex;align-items:center;gap:8px;width:100%;flex-wrap:wrap;margin-bottom:12px;">
                        <input type="text" id="coupon" placeholder="Discount code" style="flex:1;min-width:0;">
                        <button id="apply-coupon-btn" onclick="applyCoupon()">Apply</button>
                    </div>
                    <span id="applied-coupon-chip" style="display:none;align-items:center;background:#f1f3f6;border-radius:16px;padding:2px 10px 2px 8px;font-size:0.95em;color:#333;margin-top:4px;margin-bottom:8px;">
                        <span id="applied-coupon-code" style="font-weight:500;"></span>
                        <button id="remove-coupon-chip-btn" onclick="removeCoupon()" style="background:none;border:none;color:#888;font-size:1.1em;cursor:pointer;margin-left: 0px;line-height:1;padding: 5px;">&#10005;</button>
                    </span>
                </div>

                <div id="checkout-coupon-message" class="coupon-message" style="margin-top:4px;font-size:0.85rem;"></div>

                <!-- ORDER NOTES -->
                <div class="order-notes-row" style="margin-top:12px;margin-bottom:12px;">
                    <label for="orderNotes" style="font-size:13px;color:#878787;margin-bottom:4px;display:block;">Order Notes (optional)</label>
                    <textarea id="orderNotes" placeholder="Add any instructions or notes for your order..." style="width:100%;min-height:48px;padding:8px 10px;border-radius:2px;border:1px solid #e0e0e0;font-size:14px;"></textarea>
                </div>

                <!-- TOTALS -->
                <div class="price-row">
                    <span>Subtotal</span><span>₹<span id="subtotal">0.00</span></span>
                </div>

                <div class="price-row">
                    <span>Shipping</span><span class="free">Free</span>
                </div>

                <div class="price-row">
                    <span>Tax</span><span>₹<span id="tax">0.00</span></span>
                </div>

                <div class="price-row" id="coupon-summary-row" style="display:none;">
                    <span>Discount</span><span>-₹<span id="checkout-coupon-discount">0.00</span></span>
                </div>

                <div class="price-row total">
                    <span>Total</span><span>₹<span id="total">0.00</span></span>
                </div>

                <button class="cart__checkout-button" id="place-order-btn">Place Order</button>
@push('scripts')
    <!-- Razorpay JS SDK -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const placeOrderBtn = document.getElementById('place-order-btn');
            placeOrderBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                // Disable checkout page while processing order
                document.querySelector('.checkout-wrapper').style.pointerEvents = 'none';
                document.querySelector('.checkout-wrapper').style.opacity = '0.6';
                toast('Payment is processing, please wait...', false);
                // Example: Collect order details from form/JS
                const paymentMethod = document.querySelector('input[name="payment"]:checked').nextElementSibling ? 'online' : 'cod';
                if (paymentMethod === 'cod') {
                    // Re-enable if COD (since no async processing)
                    document.querySelector('.checkout-wrapper').style.pointerEvents = '';
                    document.querySelector('.checkout-wrapper').style.opacity = '';
                    alert('COD order placed!');
                    return;
                }

                // Collect values with fallback defaults
                // Try to get user_id from session or JS variable
                let userId = window.checkoutUserId;
                if (typeof userId === 'undefined' || userId === null || userId === '') {
                    userId = '{{ session('auth.user.id', '') }}';
                }
                let guestUserId = window.guestUserId;
                if (typeof guestUserId === 'undefined' || guestUserId === null || guestUserId === '') {
                    guestUserId = '{{ session('guest_user_id', '') }}';
                }
                const addressId = document.getElementById('selectedAddressId') && document.getElementById('selectedAddressId').value ? document.getElementById('selectedAddressId').value : '';
                const couponCode = document.getElementById('coupon') && document.getElementById('coupon').value ? document.getElementById('coupon').value : '';
                const orderNotes = document.getElementById('orderNotes') && document.getElementById('orderNotes').value ? document.getElementById('orderNotes').value : '';
                let cartItems = [];
                if (Array.isArray(window.cartItems) && window.cartItems.length > 0) {
                    cartItems = window.cartItems;
                } else if (document.getElementById('checkout-summary-items') && document.getElementById('checkout-summary-items').dataset.items) {
                    try {
                        cartItems = JSON.parse(document.getElementById('checkout-summary-items').dataset.items);
                    } catch (e) {
                        cartItems = [];
                    }
                } else if (localStorage.getItem('cartItems')) {
                    try {
                        cartItems = JSON.parse(localStorage.getItem('cartItems'));
                    } catch (e) {
                        cartItems = [];
                    }
                }

                let orderData;
                try {
                    const payload = {
                        user_id: userId,
                        guest_user_id: guestUserId,
                        address_id: addressId,
                        payment_method: 'razorpay',
                        coupon_code: couponCode,
                        order_notes: orderNotes,
                        cart_items: cartItems
                    };
                    console.log('Checkout payload:', payload);
                    const res = await fetch('/api/checkout/place-order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify(payload)
                    });
                    orderData = await res.json();
                } catch (err) {
                    alert('Failed to create order.');
                    return;
                }
                //console.log('Order data from backend:', orderData.order_id, orderData.amount, orderData.key);
                if (!orderData || !orderData.order_id || !orderData.amount || !orderData.key) {
                    alert('Invalid order data.');
                    return;
                }

                // 2. Open Razorpay popup
                    const options = {
                    key: orderData.key,
                    amount: orderData.amount,
                    currency: orderData.currency || 'INR',
                    name: 'Astrologer Raju Maharaj',
                    order_id: orderData.razorpay_order_id,
                    handler: function (response) {
                        // 3. On payment success, verify payment via backend
                        fetch('/api/checkout/payment/verify', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify({
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature,
                                order_id: orderData.order_id,
                                user_id: userId,
                                guest_user_id: guestUserId,
                                address_id: addressId,
                                payment_method: 'razorpay',
                                coupon_code: couponCode,
                                order_notes: orderNotes,
                                cart_items: cartItems,
                                coupon_discount: typeof orderData.coupon_discount !== 'undefined' ? orderData.coupon_discount : '',
                                price_gst: typeof orderData.price_gst !== 'undefined' ? orderData.price_gst : '',
                                discounted_price: typeof orderData.discounted_price !== 'undefined' ? orderData.discounted_price : '',
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            // Disable checkout page while processing
                            document.querySelector('.checkout-wrapper').style.pointerEvents = 'none';
                            document.querySelector('.checkout-wrapper').style.opacity = '0.6';
                            if (data.status) {
                                toast('Payment successful! Order has been placed successfully.', false);
                                setTimeout(function() {
                                    window.location.href = '/thank-you';
                                }, 1200);
                            } else {
                                toast('Payment verification failed.', true);
                                // Re-enable checkout page if failed
                                document.querySelector('.checkout-wrapper').style.pointerEvents = '';
                                document.querySelector('.checkout-wrapper').style.opacity = '';
                            }
                        })
                        .catch(() => {
                            toast('Payment verification error.', true);
                            document.querySelector('.checkout-wrapper').style.pointerEvents = '';
                            document.querySelector('.checkout-wrapper').style.opacity = '';
                        });
                    },
                    prefill: {
                        name: (orderData.user.first_name + ' ' + orderData.user.last_name) || '',
                        email: orderData.user.email || '',
                        contact: orderData.user.mobile_no || ''
                    },
                    theme: {
                        color: '#F98700'
                    }
                };
                const rzp = new Razorpay(options);
                rzp.open();
            });
        });
    </script>
@endpush

                <p class="secure-note">🔒 100% Secure Payments</p>

            </div>

        </div>

    </div>
@endsection

@push('styles')
    <style>
        .checkout-wrapper {
            display: flex;
            gap: 24px;
            align-items: flex-start;
            padding: 24px 16px;
            border-radius: 4px;
        }

        .checkout-left {
            flex: 2.2;
        }

        .checkout-right {
            flex: 1.1;
        }

        .checkout-title {
            font-size: 20px;
            margin-bottom: 12px;
            font-weight: 600;
            color: #212121;
        }

        .checkout-card {
            background: #fff;
            border-radius: 2px;
            margin-bottom: 12px;
            overflow: hidden;
        }

        .checkout-step-disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .checkout-step-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: #fff;
            /* border-bottom: 1px solid #f0f0f0; */
            cursor: pointer;
        }

        .step-number {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #f98700;
            color: #f98700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
        }

        .step-title {
            font-size: 14px;
            margin: 0;
            font-weight: 600;
            color: #212121;
        }

        .step-action {
            margin-left: auto;
            font-size: 12px;
            font-weight: 600;
            color: #f98700;
            background: none;
            border: none;
            cursor: pointer;
            text-transform: uppercase;
            padding: 0;
        }

        .checkout-step-body {
            padding: 12px 16px 14px;
        }

        .checkout-step-body.collapsed {
            display: none;
        }

        /* Skeleton loader for address list */
        .address-skeleton-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .skeleton-card {
            position: relative;
            overflow: hidden;
            background: #f5f5f5;
        }

        .skeleton-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: linear-gradient(90deg, rgba(255,255,255,0), rgba(255,255,255,0.4), rgba(255,255,255,0));
            opacity: 0.6;
            /* Remove animation so shimmer is fixed */
            /* animation: skeleton-shimmer 1.2s ease-in-out infinite; */
            /* Instead, shimmer effect is static */
        }

        .skeleton-line {
            display: block;
            height: 10px;
            background: #e0e0e0;
            border-radius: 4px;
            margin-bottom: 6px;
            width: 100%;
        }

        .skeleton-line--short {
            width: 40%;
        }

        .skeleton-line--medium {
            width: 70%;
        }

        .skeleton-line--tag {
            width: 60px;
            height: 12px;
        }

        .skeleton-circle {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #e0e0e0;
            margin-right: 8px;
        }

        .address-list-container {
            opacity: 0;
            transition: opacity 0.2s ease-in;
        }

        .address-list-container.address-list-container--visible {
            opacity: 1;
        }

        @keyframes skeleton-shimmer {
            /* No animation needed, shimmer is static */
        }

        .logged-in-summary {
            font-size: 14px;
            color: #212121;
        }

        .logged-in-name {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .logged-in-contact span {
            font-size: 13px;
            color: #878787;
        }

        .logged-in-note {
            font-size: 12px;
            color: #878787;
            margin-top: 6px;
        }

        .customer-edit-actions {
            margin-top: 8px;
            text-align: right;
        }

        .customer-edit-actions .change-cancel {
            background: none;
            border: none;
            color: #f98700;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            padding: 0;
        }

        .form-row {
            margin-bottom: 12px;
        }

        .form-row label {
            display: block;
            font-size: 13px;
            color: #878787;
            margin-bottom: 4px;
        }

        .form-row input {
            width: 100%;
            padding: 8px 10px;
            border-radius: 2px;
            border: 1px solid #e0e0e0;
            font-size: 14px;
        }

        .form-row select {
            width: 100%;
            padding: 8px 10px;
            border-radius: 2px;
            border: 1px solid #e0e0e0;
            font-size: 14px;
            background-color: #fff;
        }

        .form-row-half {
            display: flex;
            gap: 12px;
        }

        .form-row-half>div {
            flex: 1;
        }

        .payment-methods {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .payment-option {
            display: block;
            border: 1px solid #e0e0e0;
            border-radius: 2px;
            padding: 8px 10px;
            background: #fff;
            cursor: pointer;
            font-size: 14px;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .payment-option:hover {
            border-color: #f98700;
            box-shadow: 0 0 0 1px rgba(249, 135, 0, 0.08);
        }

        .payment-option-active {
            border-color: #f98700;
            box-shadow: 0 0 0 1px rgba(249, 135, 0, 0.12);
        }

        .payment-option-main {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            position: relative;
        }

        .payment-option-main input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .payment-option-main input[type="radio"]:checked+.radio-custom::after {
            transform: scale(1);
        }

        .payment-option-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .payment-title {
            font-weight: 600;
            color: #212121;
        }

        .payment-subtitle {
            font-size: 12px;
            color: #878787;
        }

        .payment-note {
            margin-top: 10px;
            font-size: 12px;
            color: #878787;
        }

        /* Checkout summary skeleton loading */
        .summary-skeleton {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .summary-skeleton-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-skeleton-img {
            width: 40px;
            height: 40px;
            border-radius: 4px;
            background: #eee;
            animation: skeleton-shimmer 1.5s infinite linear;
        }

        .summary-skeleton-text {
            flex: 1;
        }

        .summary-skeleton-line {
            height: 8px;
            background: #eee;
            border-radius: 4px;
            margin-bottom: 6px;
            animation: skeleton-shimmer 1.5s infinite linear;
        }

        .summary-skeleton-line--title {
            width: 70%;
        }

        .summary-skeleton-line--meta {
            width: 45%;
        }

        .summary-skeleton-price {
            width: 60px;
            height: 12px;
            border-radius: 4px;
            background: #eee;
            animation: skeleton-shimmer 1.5s infinite linear;
        }

        /* OTP input boxes */
        .otp-input-group {
            display: flex;
            gap: 10px;
            margin-top: 6px;
        }

        .otp-input {
            width: 48px !important;
            height: 48px;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            border-radius: 6px;
            border: 1px solid #d0d0d0;
            background: #fafafa;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
        }

        .otp-input:focus {
            outline: none;
            border-color: #f98700;
            box-shadow: 0 0 0 1px rgba(249, 135, 0, 0.18);
            background: #fff;
            transform: translateY(-1px);
        }

        /* Address list (Flipkart-style) */
        .address-list {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .address-item {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 10px 12px;
            margin-bottom: 10px;
            transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
        }

        .address-item-selected {
            border-color: #f98700;
            box-shadow: 0 0 0 1px rgba(249, 135, 0, 0.12);
        }

        .address-item:hover {
            border-color: #f98700;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
            transform: translateY(-1px);
        }

        .address-header {
            margin-bottom: 6px;
        }

        .address-radio {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
            position: relative;
        }

        .address-radio input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .radio-custom {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid #f98700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        }

        .radio-custom::after {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #f98700;
            transform: scale(0);
            transition: transform 0.15s ease-out;
        }

        .address-radio input[type="radio"]:checked+.radio-custom::after {
            transform: scale(1);
        }

        .address-name {
            font-weight: 600;
            color: #212121;
        }

        .address-tag {
            font-size: 11px;
            text-transform: uppercase;
            border: 1px solid #e0e0e0;
            padding: 1px 6px;
            border-radius: 2px;
            color: #878787;
        }

        .address-default {
            font-size: 11px;
            text-transform: uppercase;
            border-radius: 2px;
            padding: 1px 6px;
            background: #f98700;
            color: #fff;
            margin-left: 4px;
        }

        .address-body {
            font-size: 13px;
            color: #212121;
            margin-bottom: 6px;
        }

        .address-text {
            margin: 0 0 2px;
            line-height: 1.4;
        }

        .address-phone {
            margin: 0;
            color: #878787;
        }

        .address-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 4px;
        }

        /* Primary action button (visible by default) */
        .btn-deliver {
            padding: 6px 12px;
            border-radius: 2px;
            border: none;
            background: #ff9f00;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
        }

        /* In address list, hide deliver button until hover/selected */
        .address-actions .btn-deliver {
            opacity: 0;
            visibility: hidden;
            transform: translateY(2px);
            transition: opacity 0.15s ease, transform 0.15s ease, visibility 0.15s ease;
        }

        .address-item:hover .address-actions .btn-deliver,
        .address-item.address-item-selected .address-actions .btn-deliver {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .btn-link {
            background: none;
            border: none;
            color: #f98700;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            padding: 0;
        }

        .address-add-new {
            margin-top: 4px;
            padding: 10px 0 0;
            font-size: 13px;
            font-weight: 600;
            color: #f98700;
            cursor: pointer;
            text-transform: uppercase;
        }

        .address-add-form {
            margin-top: 8px;
            background: #fff;
            border-radius: 2px;
            padding: 10px 12px 12px;
        }

        .address-type-row label {
            display: block;
            margin-bottom: 4px;
        }

        .address-type-options {
            display: flex;
            gap: 16px;
            font-size: 13px;
            color: #212121;
        }

        .address-type-options label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            position: relative;
        }

        .address-type-options label input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .address-type-options label input[type="radio"]:checked+.radio-custom::after {
            transform: scale(1);
        }

        .address-add-actions {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-save-address {
            padding: 6px 14px;
            border-radius: 2px;
            border: none;
            background: #f67f00;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
        }

        /* Address remove popup (styled similar to modal) */
        .popup {
            position: fixed;
            inset: 0;
            z-index: 1060;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .popup-content {
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            max-width: 360px;
            width: 100%;
            padding: 12px 16px 10px;
        }

        .popup-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .popup-header h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #212121;
        }

        .popup-close {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
        }

        .popup-text {
            font-size: 13px;
            color: #424242;
            margin: 4px 0 10px;
        }

        .popup-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn-remove-confirm {
            padding: 6px 12px;
            border-radius: 2px;
            border: none;
            background: #d32f2f;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
        }

        .btn-remove-cancel {
            padding: 6px 12px;
            border-radius: 2px;
            border: 1px solid #e0e0e0;
            background: #fff;
            color: #212121;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
        }

        /* Address edit modal */
        .address-modal {
            position: fixed;
            inset: 0;
            z-index: 1050;
        }

        .address-modal__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
        }

        .address-modal__content {
            position: relative;
            max-width: 520px;
            margin: 60px auto;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .address-modal__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            border-bottom: 1px solid #f0f0f0;
        }

        .address-modal__header h4 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
        }

        .address-modal__close {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            line-height: 1;
        }

        .address-modal__body {
            padding: 12px 16px 4px;
        }

        .address-modal__footer {
            padding: 10px 16px 12px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            border-top: 1px solid #f0f0f0;
        }

        @media (max-width: 767px) {
            .checkout-wrapper {
                flex-direction: column;
            }

            .checkout-right {
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        (function() {
            const hasAuthToken = @json(session()->has('auth.api_token'));
            const loggedInEmail = @json(data_get(session('auth.user'), 'email', ''));

            if (!hasAuthToken) {
                return;
            }

            const addressListContainer = document.getElementById('addressListContainer');
            const skeletonContainer = document.getElementById('addressListSkeleton');
            const emptyStateEl = document.getElementById('addressEmptyState');
            const errorEl = document.getElementById('addressError');
            const selectedAddressInput = document.getElementById('selectedAddressId');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Edit modal elements
            const editModal = document.getElementById('addressEditModal');
            const editForm = document.getElementById('addressEditForm');
            const editErrorEl = document.getElementById('addressEditError');
            const editCloseBtn = document.getElementById('addressEditCloseBtn');
            const editCancelBtn = document.getElementById('addressEditCancelBtn');
            const editSaveBtn = document.getElementById('addressEditSaveBtn');
            const editAddressIdInput = document.getElementById('editAddressId');
            const editFirstNameInput = document.getElementById('editFirstName');
            const editLastNameInput = document.getElementById('editLastName');
            const editEmailInput = document.getElementById('editEmail');
            const editPhoneInput = document.getElementById('editPhone');
            const editPincodeInput = document.getElementById('editPincode');
            const editAddressInput = document.getElementById('editAddress');
            const editLandmarkInput = document.getElementById('editLandmark');
            const editCityInput = document.getElementById('editCity');
            const editStateInput = document.getElementById('editState');
            const editCountryInput = document.getElementById('editCountry');
            const editTypeRadios = document.querySelectorAll('input[name="edit_address_type"]');

            // Add new address form elements
            const addForm = document.getElementById('address-add-form');
            const addErrorEl = document.getElementById('addressAddError');
            const addFirstNameInput = document.getElementById('addFirstName');
            const addLastNameInput = document.getElementById('addLastName');
            const addPhoneInput = document.getElementById('addPhone');
            const addPincodeInput = document.getElementById('addPincode');
            const addAddressInput = document.getElementById('addAddress');
            const addLandmarkInput = document.getElementById('addLandmark');
            const addCityInput = document.getElementById('addCity');
            const addStateInput = document.getElementById('addState');
            const addTypeRadios = document.querySelectorAll('input[name="address_type"]');
            const addSaveBtn = document.getElementById('addressAddSaveBtn');

            // Remove confirmation popup elements
            const removePopup = document.getElementById('addressRemovePopup');
            const removeConfirmBtn = document.getElementById('addressRemoveConfirmBtn');
            const removeCancelBtn = document.getElementById('addressRemoveCancelBtn');
            const removeCloseBtn = document.getElementById('addressRemoveCloseBtn');

            let pendingRemoveAddressId = null;
            let pendingRemoveButton = null;

            const addressMap = {};

            // Cached state and city lists
            let statesCache = [];
            const citiesCache = {};
            let statesLoadingPromise = null;

            if (!addressListContainer) {
                return;
            }

            function populateStateSelect(selectEl, selectedId) {
                if (!selectEl) return;

                const currentValue = selectedId != null ? String(selectedId) : '';

                selectEl.innerHTML = '';
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = 'Select State';
                selectEl.appendChild(placeholder);

                statesCache.forEach(function(state) {
                    const opt = document.createElement('option');
                    opt.value = String(state.id ?? '');
                    opt.textContent = state.name || '';
                    if (currentValue && String(state.id) === currentValue) {
                        opt.selected = true;
                    }
                    selectEl.appendChild(opt);
                });
            }

            function populateCitySelect(selectEl, stateId, selectedId) {
                if (!selectEl) return;

                const list = stateId ? (citiesCache[String(stateId)] || []) : [];
                const currentValue = selectedId != null ? String(selectedId) : '';

                selectEl.innerHTML = '';
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = list.length ? 'Select City' : 'No cities';
                selectEl.appendChild(placeholder);

                if (!list.length) {
                    selectEl.disabled = true;
                    return;
                }

                list.forEach(function(city) {
                    const opt = document.createElement('option');
                    opt.value = String(city.id ?? '');
                    opt.textContent = city.name || '';
                    if (currentValue && String(city.id) === currentValue) {
                        opt.selected = true;
                    }
                    selectEl.appendChild(opt);
                });

                selectEl.disabled = false;
            }

            function ensureStatesLoaded() {
                if (statesCache.length > 0) {
                    return Promise.resolve(statesCache);
                }

                if (statesLoadingPromise) {
                    return statesLoadingPromise;
                }

                statesLoadingPromise = fetch("{{ route('checkout.state-list') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        credentials: 'include',
                        body: JSON.stringify({
                            country_id: 101
                        }),
                    })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            return {
                                response: response,
                                data: data
                            };
                        }).catch(function() {
                            return {
                                response: response,
                                data: {
                                    status: false,
                                    message: 'Unexpected server response.'
                                }
                            };
                        });
                    })
                    .then(function(result) {
                        const ok = result.response.ok && result.data && result.data.status;

                        if (!ok) {
                            throw new Error(result.data.message || 'Unable to load states.');
                        }

                        statesCache = Array.isArray(result.data.states) ? result.data.states : [];

                        if (addStateInput) {
                            populateStateSelect(addStateInput, null);
                        }
                        if (editStateInput) {
                            populateStateSelect(editStateInput, null);
                        }

                        return statesCache;
                    })
                    .finally(function() {
                        statesLoadingPromise = null;
                    });

                return statesLoadingPromise;
            }

            function loadCitiesForState(stateId) {
                if (!stateId) {
                    return Promise.resolve([]);
                }

                const key = String(stateId);
                if (citiesCache[key]) {
                    return Promise.resolve(citiesCache[key]);
                }

                return fetch("{{ route('checkout.city-list') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        credentials: 'include',
                        body: JSON.stringify({
                            state_id: stateId
                        }),
                    })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            return {
                                response: response,
                                data: data
                            };
                        }).catch(function() {
                            return {
                                response: response,
                                data: {
                                    status: false,
                                    message: 'Unexpected server response.'
                                }
                            };
                        });
                    })
                    .then(function(result) {
                        const ok = result.response.ok && result.data && result.data.status;

                        if (!ok) {
                            throw new Error(result.data.message || 'Unable to load cities.');
                        }

                        const cities = Array.isArray(result.data.cities) ? result.data.cities : [];
                        citiesCache[key] = cities;
                        return cities;
                    });
            }

            function showSkeleton() {
                if (skeletonContainer) skeletonContainer.style.display = 'block';
                addressListContainer.style.display = 'none';
                addressListContainer.classList.remove('address-list-container--visible');
                if (emptyStateEl) emptyStateEl.style.display = 'none';
                if (errorEl) {
                    errorEl.style.display = 'none';
                    errorEl.textContent = '';
                }
            }

            function hideSkeleton() {
                if (skeletonContainer) skeletonContainer.style.display = 'none';
            }

            function closeEditModal() {
                if (!editModal) return;
                editModal.style.display = 'none';
                if (editErrorEl) {
                    editErrorEl.style.display = 'none';
                    editErrorEl.textContent = '';
                }
                if (editForm) {
                    editForm.reset();
                }
            }

            function showEditError(message) {
                if (!editErrorEl) return;
                editErrorEl.textContent = message || 'Unable to update address.';
                editErrorEl.style.display = 'block';
            }

            function showAddError(message) {
                if (!addErrorEl) return;
                addErrorEl.textContent = message || 'Unable to add address.';
                addErrorEl.style.display = 'block';
            }

            function showError(message, canRetry = true) {
                if (!errorEl) return;
                errorEl.style.display = 'block';
                errorEl.innerHTML = '';

                const text = document.createElement('span');
                text.textContent = message || 'Unable to load addresses.';
                errorEl.appendChild(text);

                if (canRetry) {
                    const retry = document.createElement('button');
                    retry.type = 'button';
                    retry.className = 'btn-link';
                    retry.style.marginLeft = '8px';
                    retry.textContent = 'Retry';
                    retry.addEventListener('click', function() {
                        retry.disabled = true;
                        fetchAddresses().finally(function() {
                            retry.disabled = false;
                        });
                    });
                    errorEl.appendChild(retry);
                }
            }

            function setSelectedAddress(id) {
                if (!id || !addressListContainer) return;

                if (selectedAddressInput) {
                    selectedAddressInput.value = String(id);
                }

                const items = addressListContainer.querySelectorAll('.address-item');
                items.forEach(function(item) {
                    const radio = item.querySelector('input[type="radio"][name="delivery_address"]');
                    const isMatch = radio && String(radio.value) === String(id);
                    item.classList.toggle('address-item-selected', !!isMatch);
                    if (radio) {
                        radio.checked = !!isMatch;
                    }
                });
            }

            function openEditModalById(id) {
                if (!editModal || !addressMap[id]) return;

                const address = addressMap[id];
                const raw = address.raw || {};

                const shippingCountry = raw.shipping_country || null;
                const shippingState = raw.shipping_state || null;
                const shippingCity = raw.shipping_city || null;

                const countryVal = typeof shippingCountry === 'object' && shippingCountry !== null ?
                    (shippingCountry.id ?? shippingCountry.name ?? '') :
                    (shippingCountry ?? '');
                const stateId = typeof shippingState === 'object' && shippingState !== null ?
                    (shippingState.id ?? '') :
                    (shippingState ?? '');
                const cityId = typeof shippingCity === 'object' && shippingCity !== null ?
                    (shippingCity.id ?? '') :
                    (shippingCity ?? '');

                const rawType = (raw.type || raw.address_type || '').toString().toLowerCase();

                if (editAddressIdInput) editAddressIdInput.value = address.id || '';
                if (editFirstNameInput) editFirstNameInput.value = raw.shipping_first_name || raw.billing_first_name ||
                    '';
                if (editLastNameInput) editLastNameInput.value = raw.shipping_last_name || raw.billing_last_name || '';
                if (editEmailInput) editEmailInput.value = raw.shipping_email || raw.billing_email || '';
                if (editPhoneInput) editPhoneInput.value = raw.shipping_phone_number || raw.billing_phone_number || '';
                if (editPincodeInput) editPincodeInput.value = raw.shipping_zip_code || raw.billing_zip_code || '';
                if (editAddressInput) editAddressInput.value = raw.shipping_address || raw.billing_address || '';
                if (editLandmarkInput) editLandmarkInput.value = raw.landmark || raw.shipping_landmark || raw.billing_landmark || '';
                if (editCountryInput) editCountryInput.value = countryVal || '';
                if (editTypeRadios && editTypeRadios.length) {
                    editTypeRadios.forEach(function(radio) {
                        const val = (radio.value || '').toLowerCase();
                        radio.checked = rawType ? (val === rawType) : (val === 'home');
                    });
                }

                if (editErrorEl) {
                    editErrorEl.style.display = 'none';
                    editErrorEl.textContent = '';
                }

                editModal.style.display = 'block';

                ensureStatesLoaded()
                    .then(function() {
                        if (editStateInput) {
                            populateStateSelect(editStateInput, stateId || null);
                        }

                        if (stateId && editCityInput) {
                            return loadCitiesForState(stateId).then(function() {
                                populateCitySelect(editCityInput, stateId, cityId || null);
                            });
                        }

                        if (editCityInput) {
                            populateCitySelect(editCityInput, null, null);
                        }
                    })
                    .catch(function() {
                        // Swallow errors here; main address list will still function.
                    });
            }

            function renderAddresses(addresses) {
                addressListContainer.innerHTML = '';
                Object.keys(addressMap).forEach(function(key) {
                    delete addressMap[key];
                });

                if (!Array.isArray(addresses) || addresses.length === 0) {
                    addressListContainer.style.display = 'none';
                    if (emptyStateEl) emptyStateEl.style.display = 'block';
                    return;
                }

                if (emptyStateEl) emptyStateEl.style.display = 'none';
                addressListContainer.style.display = 'block';

                let defaultAddressId = null;

                addresses.forEach(function(address) {
                    const id = address.id;
                    const fullName = address.full_name || 'Customer';

                    let tag = 'Home';
                    if (address.type) {
                        const t = String(address.type).toLowerCase();
                        if (t === 'home') {
                            tag = 'Home';
                        } else if (t === 'office') {
                            tag = 'Office';
                        } else if (t === 'others' || t === 'other') {
                            tag = 'Other';
                        } else {
                            tag = address.type;
                        }
                    }
                    const isDefault = !!address.is_default;
                    const phone = address.phone || '';

                    const addressLine = address.address_line || '';
                    const city = address.city || '';
                    const state = address.state || '';
                    const country = address.country || '';
                    const postalCode = address.postal_code || '';
                    const raw = address.raw || {};
                    const landmark = raw.landmark || raw.shipping_landmark || raw.billing_landmark || '';

                    const cityStatePincode = [city, state, postalCode].filter(Boolean).join(', ');

                    addressMap[id] = address;

                    const item = document.createElement('div');
                    item.className = 'address-item' + (isDefault ? ' address-item-selected' : '');

                    item.innerHTML = `
                        <div class="address-header">
                            <label class="address-radio">
                                <input type="radio" name="delivery_address" value="${String(id)}">
                                <span class="radio-custom"></span>
                                <span class="address-name">${fullName}</span>
                                <span class="address-tag">${tag}</span>
                                ${isDefault ? '<span class="address-default">Default</span>' : ''}
                            </label>
                        </div>
                        <div class="address-body">
                            <p class="address-text">${addressLine || ''}</p>
                            ${landmark ? `<p class="address-text">Landmark: ${landmark}</p>` : ''}
                            ${cityStatePincode ? `<p class="address-text">${cityStatePincode}</p>` : ''}
                            ${country ? `<p class="address-text">${country}</p>` : ''}
                            <p class="address-phone">${phone ? ('Mobile: +91-' + phone) : ''}</p>
                        </div>
                        <div class="address-actions">
                            <button type="button" class="btn-deliver">DELIVER HERE</button>
                            <button type="button" class="btn-link btn-edit-address">EDIT</button>
                            <button type="button" class="btn-link btn-remove-address">REMOVE</button>
                        </div>
                    `;

                    const radio = item.querySelector('input[type="radio"][name="delivery_address"]');
                    if (radio) {
                        radio.addEventListener('change', function() {
                            setSelectedAddress(id);
                        });
                    }

                    const deliverBtn = item.querySelector('.btn-deliver');
                    if (deliverBtn) {
                        deliverBtn.addEventListener('click', function() {
                            setSelectedAddress(id);
                            setDefaultAddress(id, deliverBtn);
                        });
                    }

                    const editBtn = item.querySelector('.btn-edit-address');
                    if (editBtn) {
                        editBtn.addEventListener('click', function() {
                            openEditModalById(id);
                        });
                    }

                    const removeBtn = item.querySelector('.btn-remove-address');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function() {
                            showRemoveAddressPopup(id, removeBtn);
                        });
                    }

                    // TODO: Wire up EDIT and REMOVE with modals / API calls.

                    if (isDefault && defaultAddressId === null) {
                        defaultAddressId = id;
                    }

                    addressListContainer.appendChild(item);
                });

                if (defaultAddressId === null && addresses.length > 0) {
                    defaultAddressId = addresses[0].id;
                }

                if (defaultAddressId !== null) {
                    setSelectedAddress(defaultAddressId);
                }

                // Smooth fade-in
                requestAnimationFrame(function() {
                    addressListContainer.classList.add('address-list-container--visible');
                });
            }

            function fetchAddresses() {
                showSkeleton();

                return fetch("{{ route('checkout.user-addresses') }}", {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                        },
                        credentials: 'include',
                    })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            return {
                                response: response,
                                data: data
                            };
                        }).catch(function() {
                            return {
                                response: response,
                                data: {
                                    status: false,
                                    message: 'Unexpected server response.'
                                }
                            };
                        });
                    })
                    .then(function(result) {
                        const ok = result.response.ok && result.data && result.data.status;

                        if (!ok) {
                            showError(result.data.message || 'Unable to load addresses.');
                            return;
                        }

                        renderAddresses(result.data.addresses || []);
                    })
                    .catch(function() {
                        showError('Unable to reach address service. Please try again.');
                    })
                    .finally(function() {
                        hideSkeleton();
                    });
            }

            // Expose a reload hook for future add/edit/remove flows
            window.reloadCheckoutAddresses = fetchAddresses;

            function setRemoveLoading(button, isLoading) {
                if (!button) return;
                button.disabled = isLoading;
                if (isLoading) {
                    button.dataset.originalText = button.innerText;
                    button.innerText = 'Removing...';
                } else if (button.dataset.originalText) {
                    button.innerText = button.dataset.originalText;
                }
            }

            function showRemoveAddressPopup(id, button) {
                if (!id) return;

                if (!removePopup) {
                    // Fallback to direct delete if popup markup is missing
                    deleteAddressById(id, button);
                    return;
                }

                pendingRemoveAddressId = id;
                pendingRemoveButton = button;
                removePopup.style.display = 'block';
            }

            function closeRemoveAddressPopup() {
                if (!removePopup) return;
                removePopup.style.display = 'none';
                pendingRemoveAddressId = null;
                pendingRemoveButton = null;
            }

            function confirmRemoveAddress() {
                if (!pendingRemoveAddressId || !pendingRemoveButton) {
                    closeRemoveAddressPopup();
                    return;
                }

                const id = pendingRemoveAddressId;
                const button = pendingRemoveButton;

                closeRemoveAddressPopup();
                deleteAddressById(id, button);
            }

            function deleteAddressById(id, button) {
                if (!id) return;
                const payload = {
                    address_id: id,
                };

                setRemoveLoading(button, true);

                fetch("{{ route('checkout.address-delete') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        credentials: 'include',
                        body: JSON.stringify(payload),
                    })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            return {
                                response: response,
                                data: data
                            };
                        }).catch(function() {
                            return {
                                response: response,
                                data: {
                                    status: false,
                                    message: 'Unexpected server response.'
                                }
                            };
                        });
                    })
                    .then(function(result) {
                        const ok = result.response.ok && result.data && result.data.status;

                        if (!ok) {
                            showError(result.data.message || 'Unable to delete address.');
                            return;
                        }

                        window.reloadCheckoutAddresses();
                    })
                    .catch(function() {
                        showError('Unable to reach address service. Please try again.');
                    })
                    .finally(function() {
                        setRemoveLoading(button, false);
                    });
            }

            function setEditLoading(isLoading) {
                if (!editSaveBtn) return;
                editSaveBtn.disabled = isLoading;
                if (isLoading) {
                    editSaveBtn.dataset.originalText = editSaveBtn.innerText;
                    editSaveBtn.innerText = 'Saving...';
                } else if (editSaveBtn.dataset.originalText) {
                    editSaveBtn.innerText = editSaveBtn.dataset.originalText;
                }
            }

            function setDefaultLoading(button, isLoading) {
                if (!button) return;
                button.disabled = isLoading;
                if (isLoading) {
                    button.dataset.originalText = button.innerText;
                    button.innerText = 'SETTING...';
                } else if (button.dataset.originalText) {
                    button.innerText = button.dataset.originalText;
                }
            }

            function setAddLoading(isLoading) {
                if (!addSaveBtn) return;
                addSaveBtn.disabled = isLoading;
                if (isLoading) {
                    addSaveBtn.dataset.originalText = addSaveBtn.innerText;
                    addSaveBtn.innerText = 'Saving...';
                } else if (addSaveBtn.dataset.originalText) {
                    addSaveBtn.innerText = addSaveBtn.dataset.originalText;
                }
            }

            function setDefaultAddress(id, button) {
                if (!id) return;

                const payload = {
                    address_id: id,
                };

                setDefaultLoading(button, true);

                fetch("{{ route('checkout.address-default') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        credentials: 'include',
                        body: JSON.stringify(payload),
                    })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            return {
                                response: response,
                                data: data
                            };
                        }).catch(function() {
                            return {
                                response: response,
                                data: {
                                    status: false,
                                    message: 'Unexpected server response.'
                                }
                            };
                        });
                    })
                    .then(function(result) {
                        const ok = result.response.ok && result.data && result.data.status;

                        if (!ok) {
                            showError(result.data.message || 'Unable to update default address.');
                            return;
                        }

                        window.reloadCheckoutAddresses();
                    })
                    .catch(function() {
                        showError('Unable to reach address service. Please try again.');
                    })
                    .finally(function() {
                        setDefaultLoading(button, false);
                    });
            }

            function submitAddForm() {
                if (!addForm) return;

                if (addErrorEl) {
                    addErrorEl.style.display = 'none';
                    addErrorEl.textContent = '';
                }

                const firstName = addFirstNameInput ? addFirstNameInput.value.trim() : '';
                const lastName = addLastNameInput ? addLastNameInput.value.trim() : '';
                const phone = addPhoneInput ? addPhoneInput.value.trim() : '';
                const pincode = addPincodeInput ? addPincodeInput.value.trim() : '';
                const address = addAddressInput ? addAddressInput.value.trim() : '';
                const landmark = addLandmarkInput ? addLandmarkInput.value.trim() : '';
                const city = addCityInput ? addCityInput.value.trim() : '';
                const state = addStateInput ? addStateInput.value.trim() : '';

                let type = 'home';
                if (addTypeRadios && addTypeRadios.length) {
                    addTypeRadios.forEach(function(radio) {
                        if (radio.checked && radio.value) {
                            type = radio.value;
                        }
                    });
                }

                if (!firstName || !lastName || !phone || !pincode || !address || !city || !state) {
                    showAddError('Please fill in all required fields.');
                    return;
                }

                const payload = {
                    first_name: firstName,
                    last_name: lastName,
                    email: loggedInEmail || '',
                    phone: phone,
                    country: '1',
                    state: state,
                    city: city,
                    pincode: pincode,
                    address: address,
                    landmark: landmark,
                    type: type,
                };

                setAddLoading(true);

                fetch("{{ route('checkout.address-save') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        credentials: 'include',
                        body: JSON.stringify(payload),
                    })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            return {
                                response: response,
                                data: data
                            };
                        }).catch(function() {
                            return {
                                response: response,
                                data: {
                                    status: false,
                                    message: 'Unexpected server response.'
                                }
                            };
                        });
                    })
                    .then(function(result) {
                        const ok = result.response.ok && result.data && result.data.status;

                        if (!ok) {
                            showAddError(result.data.message || 'Unable to add address.');
                            return;
                        }

                        hideAddAddressForm();

                        if (addPhoneInput) addPhoneInput.value = '';
                        if (addPincodeInput) addPincodeInput.value = '';
                        if (addAddressInput) addAddressInput.value = '';
                        if (addLandmarkInput) addLandmarkInput.value = '';
                        if (addFirstNameInput) addFirstNameInput.value = '';
                        if (addLastNameInput) addLastNameInput.value = '';
                        if (addCityInput) addCityInput.value = '';
                        if (addStateInput) addStateInput.value = '';

                        if (addTypeRadios && addTypeRadios.length) {
                            addTypeRadios.forEach(function(radio) {
                                radio.checked = (radio.value === 'home');
                            });
                        }

                        window.reloadCheckoutAddresses();
                    })
                    .catch(function() {
                        showAddError('Unable to reach address service. Please try again.');
                    })
                    .finally(function() {
                        setAddLoading(false);
                    });
            }

            function submitEditForm() {
                if (!editForm) return;

                if (!editAddressIdInput || !editAddressIdInput.value) {
                    showEditError('Missing address identifier.');
                    return;
                }

                const payload = {
                    address_id: editAddressIdInput.value,
                    first_name: editFirstNameInput ? editFirstNameInput.value.trim() : '',
                    last_name: editLastNameInput ? editLastNameInput.value.trim() : '',
                    email: editEmailInput ? editEmailInput.value.trim() : '',
                    phone: editPhoneInput ? editPhoneInput.value.trim() : '',
                    country: editCountryInput ? editCountryInput.value.trim() : '',
                    state: editStateInput ? editStateInput.value.trim() : '',
                    city: editCityInput ? editCityInput.value.trim() : '',
                    pincode: editPincodeInput ? editPincodeInput.value.trim() : '',
                    address: editAddressInput ? editAddressInput.value.trim() : '',
                    landmark: editLandmarkInput ? editLandmarkInput.value.trim() : '',
                };

                if (editTypeRadios && editTypeRadios.length) {
                    let type = '';
                    editTypeRadios.forEach(function(radio) {
                        if (radio.checked && radio.value) {
                            type = radio.value;
                        }
                    });
                    if (type) {
                        payload.type = type;
                    }
                }

                // Basic client-side validation
                if (!payload.first_name || !payload.last_name || !payload.email || !payload.phone || !payload.country || !payload.state || !
                    payload.city || !payload.pincode || !payload.address) {
                    showEditError('Please fill in all required fields.');
                    return;
                }

                setEditLoading(true);

                fetch("{{ route('checkout.address-update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        credentials: 'include',
                        body: JSON.stringify(payload),
                    })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            return {
                                response: response,
                                data: data
                            };
                        }).catch(function() {
                            return {
                                response: response,
                                data: {
                                    status: false,
                                    message: 'Unexpected server response.'
                                }
                            };
                        });
                    })
                    .then(function(result) {
                        const ok = result.response.ok && result.data && result.data.status;

                        if (!ok) {
                            showEditError(result.data.message || 'Unable to update address.');
                            return;
                        }

                        closeEditModal();
                        window.reloadCheckoutAddresses();
                    })
                    .catch(function() {
                        showEditError('Unable to reach address service. Please try again.');
                    })
                    .finally(function() {
                        setEditLoading(false);
                    });
            }

            if (editCloseBtn) {
                editCloseBtn.addEventListener('click', function() {
                    closeEditModal();
                });
            }

            if (editCancelBtn) {
                editCancelBtn.addEventListener('click', function() {
                    closeEditModal();
                });
            }

            if (editSaveBtn) {
                editSaveBtn.addEventListener('click', function() {
                    submitEditForm();
                });
            }

            if (removeConfirmBtn) {
                removeConfirmBtn.addEventListener('click', function() {
                    confirmRemoveAddress();
                });
            }

            if (removeCancelBtn) {
                removeCancelBtn.addEventListener('click', function() {
                    closeRemoveAddressPopup();
                });
            }

            if (removeCloseBtn) {
                removeCloseBtn.addEventListener('click', function() {
                    closeRemoveAddressPopup();
                });
            }

            if (addSaveBtn) {
                addSaveBtn.addEventListener('click', function() {
                    submitAddForm();
                });
            }

            // Preload state list for add/edit dropdowns and wire state change handlers
            ensureStatesLoaded().catch(function() {
                // Ignore preload errors; user-facing errors will show on demand.
            });

            if (addStateInput && addCityInput) {
                addStateInput.addEventListener('change', function() {
                    const stateId = this.value;
                    // Clear current city options
                    populateCitySelect(addCityInput, null, null);
                    if (!stateId) {
                        return;
                    }

                    loadCitiesForState(stateId)
                        .then(function() {
                            populateCitySelect(addCityInput, stateId, null);
                        })
                        .catch(function() {
                            // Swallow error; city dropdown will remain disabled.
                        });
                });
            }

            if (editStateInput && editCityInput) {
                editStateInput.addEventListener('change', function() {
                    const stateId = this.value;
                    populateCitySelect(editCityInput, null, null);
                    if (!stateId) {
                        return;
                    }

                    loadCitiesForState(stateId)
                        .then(function() {
                            populateCitySelect(editCityInput, stateId, null);
                        })
                        .catch(function() {
                            // Swallow error; city dropdown will remain disabled.
                        });
                });
            }

            // Initial load
            fetchAddresses();
        })();

        function showAddAddressForm() {
            var form = document.getElementById('address-add-form');
            if (form) {
                form.style.display = 'block';
            }
        }

        function hideAddAddressForm() {
            var form = document.getElementById('address-add-form');
            if (form) {
                form.style.display = 'none';
            }
        }

        // Collapse / expand checkout steps
        document.addEventListener('DOMContentLoaded', function() {
            var headers = document.querySelectorAll('.checkout-step-header');
            headers.forEach(function(header) {
                header.addEventListener('click', function(e) {
                    // Don't toggle when clicking on explicit action buttons like CHANGE
                    if (e.target.closest('.step-action')) {
                        return;
                    }

                    // Keep step 1 (LOGIN / CUSTOMER DETAILS) always expanded
                    var stepNumberEl = header.querySelector('.step-number');
                    if (stepNumberEl && stepNumberEl.textContent.trim() === '1') {
                        return;
                    }

                    var body = header.nextElementSibling;
                    if (!body || !body.classList.contains('checkout-step-body')) return;

                    body.classList.toggle('collapsed');
                });
            });
        });

        // --- OTP Checkout AJAX Flow ---
        (function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const mobileInput = document.getElementById('checkout-mobile-input');
            const loginMobile = document.getElementById('checkout-login-mobile');
            const loginOtp = document.getElementById('checkout-login-otp');
            const otpInputs = Array.from(document.querySelectorAll('.otp-input'));
            const sendOtpBtn = document.getElementById('btn-checkout-send-otp');
            const verifyOtpBtn = document.getElementById('btn-checkout-verify-otp');
            const changeMobileBtn = document.getElementById('btn-checkout-change-mobile');
            const resendOtpBtn = document.getElementById('btn-checkout-resend-otp');
            const alertBox = document.getElementById('checkout-otp-alert');
            const stepMobile = document.getElementById('step-mobile');
            const stepLogin = document.getElementById('step-login');
            const resendTimer = document.getElementById('checkout-resend-timer');

            let resendCountdown = null;

            function getOtpValue() {
                if (!otpInputs || !otpInputs.length) return (loginOtp?.value || '').trim();
                const value = otpInputs.map(function(input) {
                    return (input.value || '').trim();
                }).join('');
                if (loginOtp) loginOtp.value = value;
                return value;
            }

            function clearOtpInputs() {
                if (otpInputs && otpInputs.length) {
                    otpInputs.forEach(function(input) {
                        input.value = '';
                    });
                    otpInputs[0].focus();
                }
                if (loginOtp) loginOtp.value = '';
            }

            function setLoading(button, isLoading) {
                if (!button) return;
                button.disabled = isLoading;
                if (isLoading) {
                    button.dataset.originalText = button.innerText;
                    button.innerText = 'Please wait...';
                } else if (button.dataset.originalText) {
                    button.innerText = button.dataset.originalText;
                }
            }

            function showAlert(message, isError = false) {
                if (!alertBox) return;
                alertBox.style.display = 'block';
                alertBox.textContent = message;
                alertBox.style.color = isError ? '#d32f2f' : '#2e7d32';
            }

            function clearAlert() {
                if (!alertBox) return;
                alertBox.style.display = 'none';
                alertBox.textContent = '';
            }

            function startResendCountdown(seconds) {
                if (!resendTimer) return;
                let remaining = seconds;
                resendTimer.style.display = 'inline';
                resendOtpBtn.style.pointerEvents = 'none';
                resendOtpBtn.style.opacity = '0.5';

                resendTimer.textContent = '(Resend available in ' + remaining + 's)';

                if (resendCountdown) clearInterval(resendCountdown);
                resendCountdown = setInterval(function() {
                    remaining -= 1;
                    if (remaining <= 0) {
                        clearInterval(resendCountdown);
                        resendTimer.style.display = 'none';
                        resendOtpBtn.style.pointerEvents = 'auto';
                        resendOtpBtn.style.opacity = '1';
                    } else {
                        resendTimer.textContent = '(Resend available in ' + remaining + 's)';
                    }
                }, 1000);
            }

            function postJson(url, payload, onSuccess) {
                clearAlert();
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        credentials: 'include',
                        body: JSON.stringify(payload),
                    })
                    .then(async (response) => {
                        const data = await response.json().catch(() => ({
                            success: false,
                            message: 'Unexpected server response.'
                        }));

                        if (!response.ok || data.success === false) {
                            const message = data.message || 'Something went wrong. Please try again.';
                            showAlert(message, true);
                            return;
                        }

                        onSuccess(data);
                    })
                    .catch(() => {
                        showAlert('Unable to reach authentication service. Please try again.', true);
                    });
            }

            if (sendOtpBtn) {
                sendOtpBtn.addEventListener('click', function() {
                    const mobile = (mobileInput?.value || '').trim();
                    if (!mobile) {
                        showAlert('Please enter your mobile number.', true);
                        return;
                    }

                    setLoading(sendOtpBtn, true);

                    postJson("{{ route('login.otp.request') }}", {
                        mobile_no: mobile,
                        country_code: '91',
                        context: 'checkout',
                    }, function(data) {
                        showAlert(data.message || 'OTP sent successfully.', false);
                        if (loginMobile) loginMobile.value = mobile;
                        if (stepMobile && stepLogin) {
                            stepMobile.style.display = 'none';
                            stepLogin.style.display = 'block';
                        }
                        startResendCountdown(30);
                    });

                    setTimeout(function() {
                        setLoading(sendOtpBtn, false);
                    }, 600);
                });
            }

            if (verifyOtpBtn) {
                verifyOtpBtn.addEventListener('click', function() {
                    const mobile = (loginMobile?.value || '').trim();
                    const otp = getOtpValue();

                    if (!otp) {
                        showAlert('Please enter the OTP.', true);
                        return;
                    }

                    setLoading(verifyOtpBtn, true);

                    postJson("{{ route('login.otp.verify') }}", {
                        mobile_no: mobile,
                        country_code: '+91',
                        otp: otp,
                        context: 'checkout',
                    }, function(data) {
                        showAlert(data.message || 'Logged in successfully.', false);
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.reload();
                        }
                    });

                    setTimeout(function() {
                        setLoading(verifyOtpBtn, false);
                    }, 600);
                });
            }

            if (changeMobileBtn) {
                changeMobileBtn.addEventListener('click', function() {
                    if (stepMobile && stepLogin) {
                        stepLogin.style.display = 'none';
                        stepMobile.style.display = 'block';
                        clearAlert();
                        clearOtpInputs();
                    }
                });
            }

            if (otpInputs && otpInputs.length) {
                otpInputs.forEach(function(input, index) {
                    input.addEventListener('input', function(e) {
                        let value = (e.target.value || '').replace(/[^0-9]/g, '');
                        if (value.length > 1) {
                            value = value.slice(-1);
                        }
                        e.target.value = value;

                        if (value && index < otpInputs.length - 1) {
                            otpInputs[index + 1].focus();
                        }
                    });

                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Backspace' && !e.target.value && index > 0) {
                            otpInputs[index - 1].focus();
                        }
                        if (e.key === 'ArrowLeft' && index > 0) {
                            e.preventDefault();
                            otpInputs[index - 1].focus();
                        }
                        if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                            e.preventDefault();
                            otpInputs[index + 1].focus();
                        }
                    });
                });
            }

            if (resendOtpBtn) {
                resendOtpBtn.addEventListener('click', function() {
                    const mobile = (loginMobile?.value || '').trim();
                    if (!mobile) {
                        showAlert('Mobile number is missing. Please go back and enter it again.', true);
                        return;
                    }

                    setLoading(resendOtpBtn, true);

                    postJson("{{ route('login.otp.resend') }}", {
                        mobile_no: mobile,
                        country_code: '+91',
                        context: 'checkout',
                    }, function(data) {
                        showAlert(data.message || 'OTP resent.', false);
                        startResendCountdown(30);
                    });

                    setTimeout(function() {
                        setLoading(resendOtpBtn, false);
                    }, 600);
                });
            }
        })();

        // --- Checkout Order Summary with Cart API ---
        (function() {
            const summaryBox = document.querySelector('.summary-box');
            const itemsContainer = document.getElementById('checkout-summary-items');
            const subtotalEl = document.getElementById('subtotal');
            const taxEl = document.getElementById('tax');
            const totalEl = document.getElementById('total');
            const placeOrderBtn = summaryBox ? summaryBox.querySelector('.cart__checkout-button') : null;
            const dynamicCouponsContainer = document.getElementById('checkout-dynamic-coupons');

            if (!summaryBox || !itemsContainer) {
                return;
            }

            function formatCurrency(amount) {
                return Number(amount || 0).toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function showSummarySkeleton() {
                itemsContainer.innerHTML = `
                    <div class="summary-skeleton">
                        <div class="summary-skeleton-item">
                            <div class="summary-skeleton-img"></div>
                            <div class="summary-skeleton-text">
                                <div class="summary-skeleton-line summary-skeleton-line--title"></div>
                                <div class="summary-skeleton-line summary-skeleton-line--meta"></div>
                            </div>
                            <div class="summary-skeleton-price"></div>
                        </div>
                        <div class="summary-skeleton-item">
                            <div class="summary-skeleton-img"></div>
                            <div class="summary-skeleton-text">
                                <div class="summary-skeleton-line summary-skeleton-line--title"></div>
                                <div class="summary-skeleton-line summary-skeleton-line--meta"></div>
                            </div>
                            <div class="summary-skeleton-price"></div>
                        </div>
                    </div>`;
            }

            function renderEmpty() {
                itemsContainer.innerHTML = '<p class="logged-in-note">Your cart is empty.</p>';
                if (subtotalEl) subtotalEl.textContent = '0.00';
                if (taxEl) taxEl.textContent = '0.00';
                if (totalEl) totalEl.textContent = '0.00';
                if (placeOrderBtn) {
                    placeOrderBtn.disabled = true;
                    placeOrderBtn.classList.add('disabled');
                }
            }

            function renderSummary(items) {
                if (!items || !items.length) {
                    renderEmpty();
                    return;
                }

                let subtotal = 0;
                let html = '';
                console.log('Rendering summary for items:', items);
                items.forEach(function(item) {
                    const product = item.product || {};
                    const unitPrice = parseFloat(product.price || item.amount || 0) || 0;
                    const comparePriceRaw = product.compare_at_price ? parseFloat(product.compare_at_price) : null;
                    const quantity = parseInt(item.quantity || 1, 10) || 1;
                    const lineTotal = unitPrice * quantity;
                    subtotal += lineTotal;

                    const imageUrl = product.image_url || '/assets/images/product-1.jpg';
                    const name = product.name || 'Product';
                    const options = product.options_text || '';
                    const metaParts = ['Qty: ' + quantity];
                    if (options) {
                        metaParts.push(options);
                    }
                    const metaText = metaParts.join(' • ');

                    const lineCompareTotal = comparePriceRaw ? (comparePriceRaw * quantity) : null;

                    html += `
                        <div class="summary-item">
                            <img src="${imageUrl}" alt="${name}">
                            <div>
                                <p class="product-name">${name}</p>
                                <span class="product-meta">${metaText}</span>
                            </div>
                            <div class="summary-price">
                                <strong>₹${formatCurrency(lineTotal)}</strong>
                                ${lineCompareTotal && lineCompareTotal > lineTotal ? `<span class="summary-compare">₹${formatCurrency(lineCompareTotal)}</span>` : ''}
                            </div>
                        </div>`;
                });

                itemsContainer.innerHTML = html;

                const taxAmount = subtotal * 0.03;
                const total = subtotal + taxAmount;

                if (subtotalEl) subtotalEl.textContent = formatCurrency(subtotal);
                if (taxEl) taxEl.textContent = formatCurrency(taxAmount);
                if (totalEl) totalEl.textContent = formatCurrency(total);
                if (placeOrderBtn) {
                    placeOrderBtn.disabled = false;
                    placeOrderBtn.classList.remove('disabled');
                }
            }

            function fetchCheckoutCart() {
                showSummarySkeleton();

                fetch('/api/cart', {
                        credentials: 'include'
                    })
                    .then(function(response) {
                        return response.json().then(function(data) {
                            return {
                                response: response,
                                data: data
                            };
                        }).catch(function() {
                            return {
                                response: response,
                                data: {
                                    status: 'error',
                                    message: 'Unexpected server response.'
                                }
                            };
                        });
                    })
                    .then(function(result) {
                        if (!result.response.ok || !result.data || result.data.status !== 'success') {
                            renderEmpty();
                            window.cartItems = [];
                            return;
                        }
                        const items = result.data.data || [];
                        window.cartItems = items;
                        renderSummary(items);
                    })
                    .catch(function() {
                        renderEmpty();
                        window.cartItems = [];
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
            function showToast(message, isError = false) {
                if (!message) return;

                let toast = document.getElementById('checkout-toast');
                if (!toast) {
                    toast = document.createElement('div');
                    toast.id = 'checkout-toast';
                    toast.style.position = 'fixed';
                    toast.style.left = '50%';
                    toast.style.bottom = '24px';
                    toast.style.transform = 'translateX(-50%)';
                    toast.style.zIndex = '9999';
                    toast.style.padding = '10px 16px';
                    toast.style.borderRadius = '4px';
                    toast.style.fontSize = '0.9rem';
                    toast.style.color = '#fff';
                    toast.style.boxShadow = '0 2px 6px rgba(0,0,0,0.25)';
                    toast.style.maxWidth = '90%';
                    toast.style.textAlign = 'center';
                    document.body.appendChild(toast);
                }

                toast.textContent = message;
                toast.style.backgroundColor = isError ? '#d32f2f' : '#2e7d32';
                toast.style.display = 'block';

                clearTimeout(toast._hideTimer);
                toast._hideTimer = setTimeout(function() {
                    toast.style.display = 'none';
                }, 3000);
            }

            function bindOfferCopyButtons() {
                const offers = document.querySelectorAll('.summary-box .offer');
                offers.forEach(function(offer) {
                    const code = offer.getAttribute('data-code');
                    const copyBtn = offer.querySelector('.copy-code');
                    const copiedEl = offer.querySelector('.copied');
                    if (!code || !copyBtn) return;

                    copyBtn.addEventListener('click', function() {
                        if (!navigator.clipboard || !navigator.clipboard.writeText) {
                            showToast('Unable to copy code. Please copy it manually: ' + code, true);
                            return;
                        }
                        navigator.clipboard.writeText(code).then(function() {
                            if (copiedEl) {
                                copyBtn.style.display = 'none';
                                copiedEl.style.display = 'block';
                                setTimeout(function() {
                                    copiedEl.style.display = '';
                                    copyBtn.style.display = '';
                                }, 1500);
                            }
                        });
                    });
                });
            }

            window.applyCoupon = async function() {
                const codeInput = document.getElementById('coupon');
                const btn = document.getElementById('apply-coupon-btn');
                const messageEl = document.getElementById('checkout-coupon-message');
                if (!codeInput || !btn) return;

                const rawCode = (codeInput.value || '').trim();
                if (!rawCode) {
                    toast('Please enter a coupon code.', true);
                    return;
                }

                const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : '';

                btn.disabled = true;
                const originalText = btn.textContent;
                btn.textContent = 'Applying...';
                // No need to clear messageEl, toast will be used

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
                            coupon_code: rawCode,
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

                    const payload = (data && data.data && typeof data.data === 'object') ? data.data : data;

                    const discountRaw = payload.discount_amount ?? payload.discount ?? 0;
                    const grandTotalRaw = payload.grand_total ?? payload.total ?? payload.payable_amount ?? null;

                    const discountRowEl = document.getElementById('coupon-summary-row');
                    const discountValueEl = document.getElementById('checkout-coupon-discount');
                    const totalEl = document.getElementById('total');

                    if (discountRowEl && discountValueEl && typeof discountRaw === 'number') {
                        discountRowEl.style.display = discountRaw > 0 ? 'flex' : 'none';
                        discountValueEl.textContent = formatCurrency(discountRaw);
                    }

                    // Correct calculation: Total = (Subtotal - Discount) + Tax, Tax = 3% of (Subtotal - Discount)
                    const subtotalEl = document.getElementById('subtotal');
                    const taxEl = document.getElementById('tax');
                    let subtotal = 0;
                    if (subtotalEl && !isNaN(Number(subtotalEl.textContent.replace(/,/g, '')))) {
                        subtotal = Number(subtotalEl.textContent.replace(/,/g, ''));
                    }
                    const discountedSubtotal = subtotal - discountRaw;
                    const tax = discountedSubtotal * 0.03;
                    if (taxEl) taxEl.textContent = formatCurrency(tax);
                    if (totalEl) totalEl.textContent = formatCurrency(discountedSubtotal + tax);

                    toast(data.message || 'Coupon applied successfully.', false);
                } catch (error) {
                    console.error(error);
                    toast('Network error while applying coupon. Please try again.', true);
                } finally {
                    btn.disabled = false;
                    btn.textContent = originalText;
                    if (window._updateCouponUI) window._updateCouponUI();
                }
            };

            window.removeCoupon = async function() {
                const codeInput = document.getElementById('coupon');
                const chip = document.getElementById('applied-coupon-chip');
                const chipCode = document.getElementById('applied-coupon-code');
                const messageEl = document.getElementById('checkout-coupon-message');
                if (!codeInput || !chip) return;
                const rawCode = (codeInput.value || '').trim();
                if (!rawCode) {
                    toast('No coupon to remove.', true);
                    return;
                }
                const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : '';
                chip.style.opacity = '0.6';
                // No need to clear messageEl, toast will be used
                try {
                    const response = await fetch('remove-coupon', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        credentials: 'include',
                        body: JSON.stringify({ coupon_code: rawCode }),
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
                    // Clear coupon UI
                    codeInput.value = '';
                    chipCode.textContent = '';
                    chip.style.display = 'none';
                    chip.style.opacity = '';
                    const discountRowEl = document.getElementById('coupon-summary-row');
                    const discountValueEl = document.getElementById('checkout-coupon-discount');
                    const totalEl = document.getElementById('total');
                    if (discountRowEl && discountValueEl) {
                        discountRowEl.style.display = 'none';
                        discountValueEl.textContent = '0.00';
                    }
                    toast(data.message || 'Coupon removed.', false);
                    if (window._updateCouponUI) window._updateCouponUI();
                } catch (error) {
                    toast('Network error while removing coupon. Please try again.', true);
                    chip.style.opacity = '';
                }
            };

            // Show remove button if coupon is pre-filled
            document.addEventListener('DOMContentLoaded', function() {
                fetchCheckoutCart();
                fetchCoupons();
                bindOfferCopyButtons();
                const codeInput = document.getElementById('coupon');
                const chip = document.getElementById('applied-coupon-chip');
                const chipCode = document.getElementById('applied-coupon-code');
                const applyBtn = document.getElementById('apply-coupon-btn');
                // Helper to show/hide chip and input
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
                if (codeInput && chip && chipCode && applyBtn) {
                    updateCouponUI();
                }
                // Also update after coupon is applied/removed
                window._updateCouponUI = updateCouponUI;
            });
        })();
    </script>
@endpush
