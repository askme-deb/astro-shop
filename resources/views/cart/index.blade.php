@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
<div class="container cart-page mt-5 mb-5">

  <h2 class="mb-4">Shopping Cart</h2>

  <div class="cart-layout">

    <!-- LEFT -->

    <div class="container">

      <div class="cart-item" id="cartItem">
        <img src="images/product_13.png" alt="Product">

        <div class="cart-details">
          <div class="cart-title">
            <h4>Rose Gold Personalised Eternal Necklace</h4>
            <span class="close-btn" onclick="openPopup()">✕</span>
          </div>

          <div>
            <span class="price" id="price">₹2599</span>
            <span class="compare">₹5799</span>
          </div>

          <div class="qty-box">
            <button onclick="updateQty(-1)">−</button>
            <input type="number" id="qty" value="1" min="1">
            <button onclick="updateQty(1)">+</button>
          </div>

          <div class="gift-wrap">
            <input type="checkbox" id="gift" onchange="toggleGift()">
            <label for="gift"> Add gift wrap (+ ₹50)</label>
          </div>

          <div class="total">
            Total: ₹<span id="total">2599</span>
          </div>
        </div>
      </div>



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
          <a onclick="removeItem()">Remove</a>
          <a onclick="closePopup()">Cancel</a>
        </div>
      </div>
    </div>







    <!-- RIGHT -->
    <div class="cart-footer">

      <h3>Order Summary</h3>

      <div class="totals">
        <h4>Estimated total:</h4>
        <div class="totals_wrapper">
          <p class="total__total-compare-value">₹5,799.00</p>
          <p class="totals__total-value">₹2,599.00</p>
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

      <!-- OFFERS -->
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

      <div id="rest-offers" style="display:none">
        <div class="accordion">
          <div class="accordion__intro">FLAT 10% OFF above ₹2499</div>
          <div class="accordion__content offer" data-code="LOVE10">
            <div id="offer-code">LOVE10</div>
            <div class="copy-code">Copy Code</div>
            <div class="copied">Copied</div>
          </div>
        </div>
      </div>

      <div id="show-more" class="show-hide-offers">View more</div>
      <div id="hide-more" class="show-hide-offers" style="display:none">Close</div>

      <!-- Gift Wrap -->
      <div class="all_gift_wrap">
        <input type="checkbox" id="gift">
        <label for="gift">
          <strong style="color:#E9718B">Gift wrap</strong> all items (+₹50 per item)
        </label>
      </div>

      <a href="{{ route('checkout.index') }}" class="cart__checkout-button">
        Checkout Securely
      </a>

    </div>

  </div>

</div>

@endsection
