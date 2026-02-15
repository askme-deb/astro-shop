@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="checkout-wrapper">

  <!-- LEFT -->
  <div class="checkout-left">

    <h2>Checkout</h2>

    <!-- CUSTOMER -->
    <section class="checkout-card">
      <h3>Customer Information</h3>
      <div class="form-grid">
        <input type="text" placeholder="Full Name">
        <input type="email" placeholder="Email Address">
        <input type="tel" placeholder="Phone Number">
      </div>
    </section>

    <!-- SHIPPING -->
    <section class="checkout-card">
      <h3>Shipping Address</h3>
      <div class="form-grid">
        <input type="text" placeholder="Address Line">
        <input type="text" placeholder="City">
        <input type="text" placeholder="State">
        <input type="text" placeholder="Pincode">
      </div>
    </section>

    <!-- PAYMENT -->
    <section class="checkout-card">
      <h3>Payment Method</h3>

      <label class="payment-option">
        <input type="radio" name="payment" checked>
        <span>Credit / Debit Card</span>
      </label>

      <label class="payment-option">
        <input type="radio" name="payment">
        <span>UPI / Net Banking</span>
      </label>

      <label class="payment-option">
        <input type="radio" name="payment">
        <span>Cash on Delivery</span>
      </label>
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
