@extends('layouts.app')

@section('title', ucfirst(str_replace('-', ' ', $slug)) . ' | Product')

@section('content')
<div class="container my-5 product-details-page">

  <div class="row g-5">

    <!-- Product Images -->
    <div class="col-md-6">
      <div class="product-gallery">

        <!-- Wishlist -->
        <div class="wishlist-detail">
          <i class="bi bi-heart fs-4 me-2 wishlist"></i>
          <!-- <span>Add to Wishlist</span> -->
        </div>

        <!-- Main Image -->
        <div class="main-image-wrapper">
          <img id="mainImage"
            src="images/product_10.png"
            class="main-image"
            alt="Product Image">
        </div>

        <!-- Thumbnails -->
        <div class="thumb-wrapper">
          <img src="images/product_11.png" class="thumb active" onclick="changeImage(this)">
          <img src="images/product_12.png" class="thumb" onclick="changeImage(this)">
          <img src="images/product_13.png" class="thumb" onclick="changeImage(this)">
          <img src="images/product_14.png" class="thumb" onclick="changeImage(this)">
        </div>

      </div>

    </div>

    <!-- Product Info -->
    <div class="col-md-6">

      <h2 class="mb-2">Rose Gold Princess Earrings</h2>
      <div class="rating mb-2">⭐ 4.8 | 316 Reviews</div>

      <div class="price-box mb-3">
        <span class="price fs-3 fw-bold">₹3,499</span>
        <span class="old-price ms-2">₹5,799</span>
        <span class="badge bg-success ms-2">40% OFF</span>
      </div>

      <div class="product-meta mb-3">

        <div class="sku-wrap">
          <span class="label">SKU:</span>
          <span class="value">GP62105</span>
        </div>

        <div class="origin-wrap">
          <span class="label">Origin:</span>
          <span class="value">Ethiopia</span>
        </div>

      </div>


      <div class="extra-info">

        <p class="availability in-stock">
          <span class="label">Availability:</span>
          <span class="value">In Stock</span>
        </p>


      </div>



      <p class="text-muted">
        Premium 925 Sterling Silver earrings crafted with high-quality zircon stones.
      </p>



      <!-- Wrapper -->
      <div class="row g-3 align-items-end mb-3">

        <!-- Carat Selection (50%) -->
        <div class="col-md-6">
          <label for="carat" class="fw-semibold mb-1">Select Carat Weight</label>
          <select id="carat" class="form-select" onchange="updatePrice()">
            <option value="1">1 Carat</option>
            <option value="2">2 Carat</option>
            <option value="3">3 Carat</option>
            <option value="4">4 Carat</option>
          </select>
        </div>

        <!-- Quantity (50%) -->
        <div class="col-md-6">
          <label class="fw-semibold mb-1">Quantity</label>
          <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm" onclick="qtyMinus()">−</button>
            <input
              type="text"
              id="qty"
              value="1"
              class="form-control text-center"
              style="max-width:80px;"
              readonly>
            <button class="btn btn-outline-secondary btn-sm" onclick="qtyPlus()">+</button>
          </div>
        </div>

      </div>






      <div class="offer-box" onclick="toggleOffer()">
        <div class="offer-header">
          <span>🏷️ EXTRA 20% OFF with coupon</span>
          <span id="offer-arrow">▼</span>
        </div>
        <div class="offer-details" id="offer-content">
          <p>Use code: <strong>LOVE20</strong></p>
          <span class="copy-code" onclick="event.stopPropagation(); alert('Code Copied!')">Copy Code</span>
        </div>
      </div>

      <div class="delivery-check">
        <label><strong>Check Delivery Date</strong></label>
        <div class="pincode-input-group">
          <input type="number" id="pincode" placeholder="Enter 6 digit pincode">
          <button class="check-btn" onclick="checkDelivery()">Check</button>
        </div>
        <div class="delivery-result" id="delivery-msg"></div>
      </div>

      <!-- Buttons -->
      <div class="d-grid gap-3 my-4">
        <button class="btn btn-dark btn-lg" onclick="addToCart({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">
          <i class="fa fa-bag-shopping me-2"></i>Add to Cart
        </button>

        <button class="btn btn-outline-dark btn-lg" onclick="redirectBuyNow({{ $product['id'] ?? 0 }}, document.getElementById('qty').value)">
          Buy Now
        </button>
      @push('scripts')
      <script type="module">
      import { redirectBuyNow } from '/resources/js/cart-scripts.js';
      window.redirectBuyNow = redirectBuyNow;
      </script>
      @endpush
      </div>


      <!-- Trust Badges -->
      <div class="mt-4 d-flex gap-4 small text-muted">
        <span>✔ Certified Gemstone</span>
        <span>✔ Free Shipping</span>
        <span>✔ 7 Days Return</span>
      </div>

    </div>
  </div>

  <!-- Product Tabs -->
  <div class="product-tabs mt-5">
    <ul class="nav nav-tabs" role="tablist">

      <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc" role="tab">
          Description
        </button>
      </li>

      <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#details" role="tab">
          Details
        </button>
      </li>

      <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cert" role="tab">
          Certification
        </button>
      </li>

      <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews" role="tab">
          Reviews (316)
        </button>
      </li>

    </ul>

    <div class="tab-content border p-4">

      <!-- Description -->
      <div class="tab-pane fade show active" id="desc" role="tabpanel">
        <p>
          Elegant rose gold earrings designed for daily wear and special occasions.
          Crafted in 925 sterling silver with premium zircon stones.
        </p>
      </div>

      <!-- Details -->
      <div class="tab-pane fade" id="details" role="tabpanel">
        <ul>
          <li>Metal: 925 Sterling Silver</li>
          <li>Plating: Rose Gold</li>
          <li>Stone: Zircon</li>
          <li>Weight: 5.2g</li>
        </ul>
      </div>

      <!-- Certification -->
      <div class="tab-pane fade" id="cert" role="tabpanel">
        <p>
          This product is lab-certified and verified for authenticity.
          Certificate included with purchase.
        </p>
      </div>

      <!-- Reviews -->
      <div class="tab-pane fade" id="reviews" role="tabpanel">

        <!-- Review Summary -->
        <div class="review-summary mb-4">
          <h5>Customer Reviews</h5>
          <div class="d-flex align-items-center gap-2">
            <span class="fs-4 fw-bold">4.8</span>
            <span>⭐⭐⭐⭐⭐</span>
            <span class="text-muted">(316 Reviews)</span>
          </div>
        </div>

        <!-- Review Item -->
        <div class="review-item mb-4">
          <strong>Ananya S.</strong>
          <div class="text-warning">⭐⭐⭐⭐⭐</div>
          <p class="mt-2">
            Absolutely beautiful earrings. The finish and quality exceeded my expectations!
          </p>
        </div>

        <div class="review-item mb-4">
          <strong>Rahul K.</strong>
          <div class="text-warning">⭐⭐⭐⭐</div>
          <p class="mt-2">
            Loved the design and fast delivery. Perfect for gifting.
          </p>
        </div>

        <div class="review-item mb-4">
          <strong>Pooja M.</strong>
          <div class="text-warning">⭐⭐⭐⭐⭐</div>
          <p class="mt-2">
            Looks premium and classy. Worth the price.
          </p>
        </div>

        <!-- Add Review -->
        <hr>
        <h6 class="mb-3">Write a Review</h6>

        <form>
          <div class="mb-3">
            <label class="form-label">Your Rating</label>
            <select class="form-select">
              <option>★★★★★ (5)</option>
              <option>★★★★☆ (4)</option>
              <option>★★★☆☆ (3)</option>
              <option>★★☆☆☆ (2)</option>
              <option>★☆☆☆☆ (1)</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Your Review</label>
            <textarea class="form-control" rows="3" placeholder="Share your experience..."></textarea>
          </div>

          <button class="btn btn-dark">Submit Review</button>
        </form>

      </div>

    </div>
  </div>

</div>



<div class="container my-5">

  <div class="text-center section-title mb-4">
    <h2>Similar Products</h2>
  </div>


  <div class="owl-carousel bestselling-carousel">

    <div class="item">
      <div class="product-card">
        <i class="bi bi-heart wishlist" data-product-id="{{ $product['id'] ?? 0 }}"></i>
        <img src="images/product-1.jpg">
        <div class="rating">⭐ 4.8 | 316</div>
        <h6>Rose Gold Princess Earrings</h6>
        <span class="price">₹3,499</span>
        <span class="old-price">₹5,799</span>
        <div class="offer">EXTRA 16% OFF with coupon</div>
        <div class="d-grid gap-2 mt-3">
          <button class="btn btn-cart">Add to Cart</button>
          <button class="btn btn-buy">Buy Now</button>
        </div>
      </div>
    </div>

    <div class="item">
      <div class="product-card">
        <i class="bi bi-heart wishlist" data-product-id="{{ $product['id'] ?? 0 }}"></i>
        <img src="images/product-1.jpg">
        <div class="rating">⭐ 4.8 | 217</div>
        <h6>Anushka Sharma Rose Gold Bracelet</h6>
        <span class="price">₹6,499</span>
        <span class="old-price">₹12,999</span>
        <div class="offer">EXTRA 20% OFF with coupon</div>
        <div class="d-grid gap-2 mt-3">
          <button class="btn btn-cart">Add to Cart</button>
          <button class="btn btn-buy">Buy Now</button>
        </div>
      </div>
    </div>

    <!-- Add more items -->
  </div>
</div>

@endsection
