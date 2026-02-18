@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="checkout-wrapper">

        <!-- LEFT -->
        <div class="checkout-left">
            <h2 class="checkout-title">Checkout</h2>

            <!-- STEP 1: LOGIN / CUSTOMER -->
            <section class="checkout-card checkout-step">
                <div class="checkout-step-header">
                    <span class="step-number">1</span>
                    <h3 class="step-title">LOGIN / CUSTOMER DETAILS</h3>
                    <button type="button" class="step-action" onclick="showCustomerEdit()">CHANGE</button>
                </div>
                <div class="checkout-step-body">
                    <div class="logged-in-summary" id="customer-summary">
                        <div class="logged-in-name">John Doe</div>
                        <div class="logged-in-contact">
                            <span>john.doe@example.com</span>
                            <span>• +91-9876543210</span>
                        </div>
                        <div class="logged-in-note">You are logged in. Customer details are pre-filled.</div>
                    </div>

                    <div id="customer-edit-form" style="display:none;">
                        <div class="form-row">
                            <label>Full Name</label>
                            <input type="text" placeholder="Enter your full name" value="John Doe">
                        </div>
                        <div class="form-row">
                            <label>Email Address</label>
                            <input type="email" placeholder="Enter your email" value="john.doe@example.com">
                        </div>
                        <div class="form-row">
                            <label>Phone Number</label>
                            <input type="tel" placeholder="Enter your phone number" value="+91-9876543210">
                        </div>
                        <div class="customer-edit-actions">
                            <button type="button" class="change-cancel" onclick="hideCustomerEdit()">CANCEL</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- STEP 2: DELIVERY ADDRESS -->
            <section class="checkout-card checkout-step">
                <div class="checkout-step-header">
                    <span class="step-number">2</span>
                    <h3 class="step-title">DELIVERY ADDRESS</h3>
                </div>
                <div class="checkout-step-body">
                    <div class="address-list">
                        <!-- Default selected address -->
                        <div class="address-item address-item-selected">
                            <div class="address-header">
                                <label class="address-radio">
                                    <input type="radio" name="delivery_address" checked>
                                    <span class="radio-custom"></span>
                                    <span class="address-name">John Doe</span>
                                    <span class="address-tag">Home</span>
                                    <span class="address-default">Default</span>
                                </label>
                            </div>
                            <div class="address-body">
                                <p class="address-text">
                                    221B Baker Street, Near Central Park, <br>
                                    London, London - 560001
                                </p>
                                <p class="address-phone">Mobile: +91-9876543210</p>
                            </div>
                            <div class="address-actions">
                                <button type="button" class="btn-deliver">DELIVER HERE</button>
                                <button type="button" class="btn-link">EDIT</button>
                            </div>
                        </div>

                        <!-- Another saved address -->
                        <div class="address-item">
                            <div class="address-header">
                                <label class="address-radio">
                                    <input type="radio" name="delivery_address">
                                    <span class="radio-custom"></span>
                                    <span class="address-name">John Doe</span>
                                    <span class="address-tag">Work</span>
                                </label>
                            </div>
                            <div class="address-body">
                                <p class="address-text">
                                    14 MG Road, Business Tower, 3rd Floor, <br>
                                    Bengaluru, Karnataka - 560002
                                </p>
                                <p class="address-phone">Mobile: +91-9876543211</p>
                            </div>
                            <div class="address-actions">
                                <button type="button" class="btn-deliver">DELIVER HERE</button>
                                <button type="button" class="btn-link">EDIT</button>
                                <button type="button" class="btn-link">REMOVE</button>
                            </div>
                        </div>

                        <!-- Add new address CTA -->
                        <div class="address-add-new" onclick="showAddAddressForm()">
                            + Add a new address
                        </div>

                        <!-- Add new address form (hidden by default) -->
                        <div class="address-add-form" id="address-add-form" style="display:none;">
                            <div class="form-row">
                                <label>Full Name</label>
                                <input type="text" placeholder="Enter full name">
                            </div>
                            <div class="form-row form-row-half">
                                <div>
                                    <label>Mobile Number</label>
                                    <input type="tel" placeholder="10-digit mobile number">
                                </div>
                                <div>
                                    <label>Pincode</label>
                                    <input type="text" placeholder="Pincode">
                                </div>
                            </div>
                            <div class="form-row">
                                <label>Address (House no, Building, Area)</label>
                                <input type="text" placeholder="Flat / House no., Building, Street, Area">
                            </div>
                            <div class="form-row form-row-half">
                                <div>
                                    <label>City / District</label>
                                    <input type="text" placeholder="City / District">
                                </div>
                                <div>
                                    <label>State</label>
                                    <input type="text" placeholder="State">
                                </div>
                            </div>
                            <div class="form-row">
                                <label>Landmark (Optional)</label>
                                <input type="text" placeholder="Nearby landmark">
                            </div>
                            <div class="form-row address-type-row">
                                <label>Address Type</label>
                                <div class="address-type-options">
                                    <label>
                                        <input type="radio" name="address_type" checked>
                                        <span class="radio-custom"></span>
                                        Home
                                    </label>
                                    <label>
                                        <input type="radio" name="address_type">
                                        <span class="radio-custom"></span>
                                        Work
                                    </label>
                                    <label>
                                        <input type="radio" name="address_type">
                                        <span class="radio-custom"></span>
                                        Other
                                    </label>
                                </div>
                            </div>
                            <div class="address-add-actions">
                                <button type="button" class="btn-save-address">SAVE AND DELIVER HERE</button>
                                <button type="button" class="btn-link" onclick="hideAddAddressForm()">CANCEL</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- STEP 3: PAYMENT OPTIONS -->
            <section class="checkout-card checkout-step">
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

                <div class="summary-item">
                    <img src="images/product_13.png" alt="Product">
                    <div>
                        <p class="product-name">Rose Gold Personalised Eternal Necklace</p>
                        <span class="product-meta">Qty: 1 • No Charm</span>
                    </div>
                    <strong>₹2,599</strong>
                </div>

                <div class="accordion">
                    <div class="accordion__intro">EXTRA 16% OFF above ₹1999</div>
                    <div class="accordion__content offer" data-code="SWEET16">
                        <div id="offer-code">SWEET16</div>
                        <div class="copy-code">Copy Code</div>
                        <div class="copied">Copied</div>
                    </div>
                </div>

                <div class="accordion">
                    <div class="accordion__intro">FLAT 20% OFF above ₹4499</div>
                    <div class="accordion__content offer" data-code="LOVE20">
                        <div id="offer-code">LOVE20</div>
                        <div class="copy-code">Copy Code</div>
                        <div class="copied">Copied</div>
                    </div>
                </div>


                <!-- COUPON -->
                <div class="coupon-row">
                    <input type="text" id="coupon" placeholder="Discount code">
                    <button onclick="applyCoupon()">Apply</button>
                </div>

                <!-- TOTALS -->
                <div class="price-row">
                    <span>Subtotal</span><span>₹<span id="subtotal">2599</span></span>
                </div>

                <div class="price-row">
                    <span>Shipping</span><span class="free">Free</span>
                </div>

                <div class="price-row">
                    <span>Tax</span><span>₹<span id="tax">78</span></span>
                </div>

                <div class="price-row total">
                    <span>Total</span><span>₹<span id="total">2677</span></span>
                </div>

                <button class="cart__checkout-button">Place Order</button>

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

        /* Address list (Flipkart-style) */
        .address-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .address-item {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 2px;
            padding: 10px 12px;
        }

        .address-item-selected {
            border-color: #f98700;
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
            background: #fb641b;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
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
        function showCustomerEdit() {
            var summary = document.getElementById('customer-summary');
            var form = document.getElementById('customer-edit-form');
            if (summary && form) {
                summary.style.display = 'none';
                form.style.display = 'block';
            }
        }

        function hideCustomerEdit() {
            var summary = document.getElementById('customer-summary');
            var form = document.getElementById('customer-edit-form');
            if (summary && form) {
                form.style.display = 'none';
                summary.style.display = 'block';
            }
        }

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

                    var body = header.nextElementSibling;
                    if (!body || !body.classList.contains('checkout-step-body')) return;

                    body.classList.toggle('collapsed');
                });
            });
        });
    </script>
@endpush
