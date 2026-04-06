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

           <i class="bi {{ (!empty($product['is_in_wishlist']) || !empty($product['in_wishlist'])) ? 'bi-heart-fill' : 'bi-heart' }} wishlist" data-product-id="{{ $product['id'] ?? 0 }}"></i>
          <!-- <span>Add to Wishlist</span> -->
        </div>

        <!-- Main Image -->
        <div class="main-image-wrapper">
          <img id="mainImage"
            src="{{ $product['media'][0]['original_url'] ?? $product['image_url'] ?? asset('images/default-product.png') }}"
            class="main-image"
            alt="Product Image">
        </div>

        <!-- Thumbnails -->
        <div class="thumb-wrapper">
          @if(!empty($product['media']))
            @foreach($product['media'] as $key => $img)
              <img src="{{ $img['original_url'] }}" class="thumb{{ $key === 0 ? ' active' : '' }}" onclick="changeImage(this)">
            @endforeach
          @else
            <img src="{{ $product['image_url'] ?? asset('images/default-product.png') }}" class="thumb active" onclick="changeImage(this)">
          @endif
        </div>

      </div>

    </div>

    <!-- Product Info -->
    <div class="col-md-6">

      <h2 class="mb-2">{{ $product['name'] ?? 'Product Name' }}</h2>
      <div class="rating mb-2">⭐ {{ $product['rating'] ?? 'N/A' }} | {{ $product['reviews_count'] ?? '0' }} Reviews</div>

      <div class="price-box mb-3">
        @if(!empty($product['discount_price']) && $product['discount_price'] > 0)
          <span class="price fs-3 fw-bold">₹{{ number_format(($product['product_price'] ?? 0) - ($product['discount_price'] ?? 0), 2) }}</span>
          <span class="old-price ms-2">₹{{ $product['product_price'] }}</span>
          <span class="badge bg-success ms-2">{{ $product['discount_rate'] }}% OFF</span>
        @else
          <span class="price fs-3 fw-bold">₹{{ $product['product_price'] ?? $product['final_price'] ?? $product['price'] ?? '0' }}</span>
        @endif
      </div>

      <div class="product-meta mb-3">
        <div class="sku-wrap">
          <span class="label">SKU:</span>
          <span class="value">{{ $product['sku'] ?? 'N/A' }}</span>
        </div>
        <div class="origin-wrap">
          <span class="label">Carat:</span>
          <span class="value">{{ $product['carat'] ?? 'N/A' }}</span>
        </div>
        <!-- <div class="origin-wrap">
          <span class="label">Ratti:</span>
          <span class="value">{{ $product['ratti'] ?? 'N/A' }}</span>
        </div> -->
      </div>


      <div class="extra-info">

        <p class="availability {{ ($product['stock'] ?? 0) > 0 ? 'in-stock' : 'out-of-stock' }}">
          <span class="label">Availability:</span>
          <span class="value">{{ ($product['stock'] ?? 0) > 0 ? 'In Stock' : 'Out of Stock' }}</span>
        </p>


      </div>



      <p class="text-muted">
        {!! $product['sort_description'] ?? $product['short_description'] ?? 'No short description available.' !!}
      </p>



      <!-- Wrapper -->
      <div class="row g-3 align-items-end mb-3">

        <!-- Carat Selection (50%) -->
        <!-- <div class="col-md-6">
          <label for="carat" class="fw-semibold mb-1">Select Ratti Weight</label>
          <select id="carat" class="form-select" onchange="updatePrice()">
            @for($carat = 3; $carat <= 15; $carat += 0.5)
              <option value="{{ $carat }}">{{ rtrim(rtrim(number_format($carat, 1), '0'), '.') }} Ratti</option>
            @endfor
          </select>
        </div> -->

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






      @if(!empty($coupons))
        @foreach($coupons as $coupon)
          <div class="offer-box" onclick="toggleOffer()">
            <div class="offer-header">
              <span>🏷️ {{ $coupon['title'] ?? (isset($coupon['discount_value']) ? intval($coupon['discount_value']) : '') . '% OFF with coupon' }}</span>
              <span id="offer-arrow">▼</span>
            </div>
            <div class="offer-details" id="offer-content">
              <p>Use code: <strong>{{ $coupon['code'] ?? 'N/A' }}</strong></p>
              <span class="copy-code" onclick="event.stopPropagation(); navigator.clipboard.writeText('{{ $coupon['code'] ?? '' }}'); alert('Code Copied!')">Copy Code</span>
              @if(!empty($coupon['description']))
                <div class="mt-2 text-muted">{{ $coupon['description'] }}</div>
              @endif
            </div>
          </div>
        @endforeach
      @endif

      <div class="delivery-check">
        <label><strong>Check Delivery Date</strong></label>
        <div class="pincode-input-group">
          <input type="number" id="pincode" placeholder="Enter 6 digit pincode" value="{{ $pincode ?? old('pincode') ?? '' }}">
          <button class="check-btn" onclick="checkDelivery()">Check</button>
        </div>
        <div class="delivery-result" id="delivery-msg"></div>
      </div>

      <!-- Buttons -->
      <div class="d-grid gap-3 my-4">
        <button class="btn btn-dark btn-lg" onclick="addToCart({ product_id: {{ $product['id'] ?? 0 }}, quantity: document.getElementById('qty').value }, this)" {{ ($product['stock'] ?? 0) < 1 ? 'disabled' : '' }}>
          <i class="fa fa-bag-shopping me-2"></i>Add to Cart
        </button>

        <button class="btn btn-outline-dark btn-lg" onclick="buyNow({ product_id: {{ $product['id'] ?? 0 }}, quantity: document.getElementById('qty').value }, this)" {{ ($product['stock'] ?? 0) < 1 ? 'disabled' : '' }}>
          Buy Now
        </button>
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

        <li class="nav-item" role="presentation">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#specifications" role="tab">
            Specifications
          </button>
        </li>

    </ul>

    <div class="tab-content border p-4">

      <!-- Description -->
      <div class="tab-pane fade show active" id="desc" role="tabpanel">
        <p>{!! $product['long_description'] ?? $product['sort_description'] ?? $product['short_description'] ?? 'No description available.' !!}</p>
      </div>

      <!-- Details -->
      <div class="tab-pane fade" id="details" role="tabpanel">
        <div class="table-responsive">
          <table class="table table-striped table-bordered align-middle mb-0">
            <tbody>
              <tr>
                <th style="width: 30%;">SKU</th>
                <td>{{ $product['sku'] ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Carat</th>
                <td>{{ $product['carat'] ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Ratti</th>
                <td>{{ $product['ratti'] ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Stock</th>
                <td>{{ $product['stock'] ?? 'N/A' }}</td>
              </tr>
              <tr>
                <th>Type</th>
                <td>{{ $product['product_type'] ?? 'N/A' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

        <!-- Specifications -->
        <div class="tab-pane fade" id="specifications" role="tabpanel">
          @if(!empty($product['specifications']))
            <div class="table-responsive">
              <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 30%;">Specification</th>
                    <th>Description</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($product['specifications'] as $spec)
                    <tr>
                      <td class="fw-semibold">{{ $spec['title'] ?? '' }}</td>
                      <td>{{ $spec['description'] ?? '' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="alert alert-info mb-0">No specifications available.</div>
          @endif
        </div>

      <!-- Certification -->
      <div class="tab-pane fade" id="cert" role="tabpanel">
        <p>
          @if(!empty($product['is_featured']))
            This product is featured and certified.
          @else
            Certificate included with purchase.
          @endif
        </p>
      </div>

      <!-- Reviews -->
      <div class="tab-pane fade" id="reviews" role="tabpanel">
        <div class="review-summary mb-4">
          <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
              <h5 class="card-title mb-3">Customer Reviews</h5>
              <div class="d-flex align-items-center gap-3 mb-2">
                <span class="fs-2 fw-bold text-warning">
                  <i class="bi bi-star-fill"></i> {{ $product['rating'] ?? 'N/A' }}
                </span>
                <span class="text-muted">({{ $product['reviews_count'] ?? '0' }} Reviews)</span>
              </div>
            </div>
          </div>
          <div class="card mb-4 border-0 bg-light">
            <div class="card-body py-3">
              <div class="review-item text-center">
                <strong class="text-muted">No reviews yet.</strong>
              </div>
            </div>
          </div>
          <hr>
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <h6 class="mb-3">Write a Review</h6>
              <form>
                <div class="row g-3 align-items-center mb-3">
                  <div class="col-md-3">
                    <label class="form-label mb-0 fw-semibold">Your Rating</label>
                  </div>
                  <div class="col-md-9">
                    <div class="star-rating d-flex align-items-center gap-1" style="font-size: 2rem;">
                      @for ($i = 1; $i <= 5; $i++)
                        <span class="star" data-value="{{ $i }}" style="cursor:pointer;color:#ddd;transition:color 0.2s;">
                          <i class="bi bi-star-fill"></i>
                        </span>
                      @endfor
                      <input type="hidden" name="rating" id="review-rating" value="0">
                    </div>
                    <script>
                      document.addEventListener('DOMContentLoaded', function() {
                        const stars = document.querySelectorAll('.star-rating .star');
                        const ratingInput = document.getElementById('review-rating');
                        let currentRating = 0;
                        stars.forEach((star, idx) => {
                          star.addEventListener('mouseenter', function() {
                            for (let j = 0; j <= idx; j++) stars[j].style.color = '#ffc107';
                            for (let j = idx + 1; j < stars.length; j++) stars[j].style.color = '#ddd';
                          });
                          star.addEventListener('mouseleave', function() {
                            for (let j = 0; j < stars.length; j++)
                              stars[j].style.color = (j < currentRating) ? '#ffc107' : '#ddd';
                          });
                          star.addEventListener('click', function() {
                            currentRating = idx + 1;
                            ratingInput.value = currentRating;
                            for (let j = 0; j < stars.length; j++)
                              stars[j].style.color = (j < currentRating) ? '#ffc107' : '#ddd';
                          });
                        });
                      });
                    </script>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Your Review</label>
                  <textarea class="form-control" rows="3" placeholder="Share your experience..."></textarea>
                </div>
                <div class="d-grid">
                  <button class="btn btn-dark btn-lg">Submit Review</button>
                </div>
              </form>
            </div>
          </div>
      </div>

    </div>
  </div>

</div>



<div class="container my-5">
  <div class="text-center section-title mb-4">
    <h2>Similar Products</h2>
  </div>
  <div class="owl-carousel bestselling-carousel">
    @foreach($relatedProducts ?? [] as $related)
      <div class="item">
        <a href="{{ url('products/' . ($related['slug'] ?? $related['id'] ?? '')) }}" class="text-decoration-none text-dark">
          <div class="product-card">
            <i class="bi {{ (!empty($related['is_in_wishlist']) || !empty($related['in_wishlist'])) ? 'bi-heart-fill' : 'bi-heart' }} wishlist" data-product-id="{{ $related['id'] ?? 0 }}"></i>
            <img src="{{ $related['image_url'] ?? asset('images/product-1.jpg') }}" alt="{{ $related['name'] ?? 'Product' }}">
            <div class="rating">⭐ {{ $related['rating'] ?? 'N/A' }} | {{ $related['reviews_count'] ?? '0' }}</div>
            <h6>{{ $related['name'] ?? 'Product' }}</h6>
            <span class="price">₹{{ $related['final_price'] ?? $related['price'] ?? '0.00' }}</span>
            @if(!empty($related['discount_rate']) && $related['discount_rate'] !== '0.00')
              <span class="old-price ms-2">₹{{ $related['price'] ?? $related['product_price'] ?? '' }}</span>
            @endif
            <div class="offer">{{ !empty($related['discount_rate']) ? 'EXTRA ' . $related['discount_rate'] . '% OFF with coupon' : '&nbsp;' }}</div>
            <div class="d-grid gap-2 mt-3">
              <button class="btn btn-cart" onclick="event.preventDefault(); addToCart({ product_id: {{ $related['id'] ?? 0 }}, quantity: 1 }, this)">Add to Cart</button>
              <button class="btn btn-buy" onclick="event.preventDefault(); buyNow({ product_id: {{ $related['id'] ?? 0 }}, quantity: 1 }, this)">Buy Now</button>
            </div>
          </div>
        </a>
      </div>
    @endforeach







  </div>
</div>

@push('scripts')
<script type="text/javascript">
  // Sync delivery pincode from localStorage to delivery-check input
  document.addEventListener('DOMContentLoaded', function() {
    try {
      const savedPincode = window.localStorage.getItem('delivery_pincode');
      if (savedPincode) {
        var pincodeInput = document.getElementById('pincode');
        if (pincodeInput) {
          pincodeInput.value = savedPincode;
        }
      }
    } catch (e) {}
  });
  function qtyMinus() {
    var qtyInput = document.getElementById('qty');
    var value = parseInt(qtyInput.value, 10);
    if (value > 1) {
      qtyInput.value = value - 1;
    }
  }
  function qtyPlus() {
    var qtyInput = document.getElementById('qty');
    var value = parseInt(qtyInput.value, 10);
    qtyInput.value = value + 1;
  }

  function checkDelivery() {
    var pincode = document.getElementById('pincode').value;
    var msgDiv = document.getElementById('delivery-msg');
    msgDiv.innerHTML = 'Checking...';
    fetch('/api/check-delivery', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Pincode': pincode
      },
      body: JSON.stringify({ pincode: pincode })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        msgDiv.innerHTML = '<span class="text-success">' + data.message + '</span>';
      } else {
        msgDiv.innerHTML = '<span class="text-danger">' + (data.message || 'Delivery not available.') + '</span>';
      }
    })
    .catch(() => {
      msgDiv.innerHTML = '<span class="text-danger">Error checking delivery.</span>';
    });
  }
</script>
<script type="module">
  import {
    redirectBuyNow
  } from '/resources/js/cart-scripts.js';
  window.redirectBuyNow = redirectBuyNow;
</script>
@endpush
</div>
@endsection
