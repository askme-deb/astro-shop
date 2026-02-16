@extends('layouts.app')

@section('title', 'Products')

@section('content')
<!-- Page Title -->
<section class="py-4 border-bottom inner_warp">
  <div class="container">
    <h2 class="fw-bold">Shop</h2>
    @if(!empty($products))
      <p class="text-muted">Showing {{ count($products) }} products</p>
    @else
      <p class="text-muted">No products available at the moment.</p>
    @endif
  </div>
</section>

<section class="collection-filter">
  <div class="container">
    <div class="filter-row">

      <!-- Left Filters -->
      <div class="filter-left">

        <div class="filter-dropdown">
          <button class="filter-btn">Product type</button>
          <ul class="filter-menu">
            <li data-value="ring">Rings</li>
            <li data-value="necklace">Necklaces</li>
            <li data-value="bracelet">Bracelets</li>
          </ul>
        </div>

        <div class="filter-dropdown">
          <button class="filter-btn">Price</button>
          <ul class="filter-menu">
            <li data-value="0-2000">Below ₹2,000</li>
            <li data-value="2000-5000">₹2,000 – ₹5,000</li>
            <li data-value="5000+">Above ₹5,000</li>
          </ul>
        </div>

        <div class="filter-dropdown">
          <button class="filter-btn">Shop For</button>
          <ul class="filter-menu">
            <li>Men</li>
            <li>Women</li>
            <li>Kids</li>
          </ul>
        </div>

        <div class="filter-dropdown">
          <button class="filter-btn">Color</button>
          <ul class="filter-menu">
            <li>Gold</li>
            <li>Silver</li>
            <li>Rose Gold</li>
          </ul>
        </div>

        <div class="filter-dropdown">
          <button class="filter-btn">Metal</button>
          <ul class="filter-menu">
            <li>Gold</li>
            <li>Silver</li>
            <li>Platinum</li>
          </ul>
        </div>

        <div class="filter-dropdown">
          <button class="filter-btn">Stone</button>
          <ul class="filter-menu">
            <li>Diamond</li>
            <li>Ruby</li>
            <li>Sapphire</li>
          </ul>
        </div>

        <div class="filter-dropdown">
          <button class="filter-btn">Style</button>
          <ul class="filter-menu">
            <li>Classic</li>
            <li>Modern</li>
            <li>Minimal</li>
          </ul>
        </div>

        <div class="filter-dropdown">
          <button class="filter-btn">Sub Category</button>
          <ul class="filter-menu">
            <li>Daily Wear</li>
            <li>Party Wear</li>
            <li>Wedding</li>
          </ul>
        </div>

      </div>

      <!-- Right Sort -->
      <div class="filter-right">
        <label>Sort by:</label>
        <select id="sortSelect">
          <option value="best">Best selling</option>
          <option value="new">New arrivals</option>
          <option value="price-low">Price: Low to High</option>
          <option value="price-high">Price: High to Low</option>
        </select>
      </div>

    </div>
  </div>
</section>


<!-- Products -->
<div class="container my-5">
  <div class="row g-4 d-md-flex">
    @forelse($products ?? [] as $product)
      <div class="col-md-3 col-sm-6">
        <div class="product-card">
          <i class="bi bi-heart wishlist"></i>
          <img src="{{ $product['image_url'] ?? asset('assets/images/product-1.jpg') }}" alt="{{ $product['name'] ?? 'Product' }}">
          <div class="rating">
            ⭐ {{ $product['rating'] ?? '4.5' }}
          </div>
          <h6 class="mt-2">{{ $product['name'] ?? 'Product' }}</h6>
          <div>
            <span class="price">₹{{ $product['final_price'] ?? $product['total_price'] ?? $product['price'] ?? '0.00' }}</span>
            @if(!empty($product['discount_rate']) && $product['discount_rate'] !== '0.00')
              <span class="old-price ms-2">₹{{ $product['price'] ?? $product['product_price'] ?? '' }}</span>
            @endif
          </div>
          @if(!empty($product['discount_rate']) && $product['discount_rate'] !== '0.00')
            <div class="offer">Save {{ $product['discount_rate'] }}%</div>
          @else
            <div class="offer">&nbsp;</div>
          @endif
          <div class="d-grid gap-2 mt-3">
            <button class="btn btn-cart" onclick="addToCart({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Add to Cart</button>
            <button class="btn btn-buy" onclick="buyNow({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)">Buy Now</button>
          </div>
        </div>
      </div>
    @empty
      <p>No products found.</p>
    @endforelse
  </div>



</div>

@php
  $currentPage = (int) (($pagination['current_page'] ?? 1));
  $lastPage = (int) (($pagination['last_page'] ?? $currentPage));
@endphp

@if($lastPage > 1)
  <div class="pagination-wrapper">
    <ul class="pagination">
      {{-- Previous page --}}
      <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
        <a href="{{ $currentPage <= 1 ? '#' : route('products.index', ['page' => $currentPage - 1]) }}" data-page="prev">‹</a>
      </li>

      {{-- Page numbers --}}
      @for($page = 1; $page <= $lastPage; $page++)
        <li class="page-item {{ $page === $currentPage ? 'active' : '' }}">
          <a href="{{ route('products.index', ['page' => $page]) }}" data-page="{{ $page }}">{{ $page }}</a>
        </li>
      @endfor

      {{-- Next page --}}
      <li class="page-item {{ $currentPage >= $lastPage ? 'disabled' : '' }}">
        <a href="{{ $currentPage >= $lastPage ? '#' : route('products.index', ['page' => $currentPage + 1]) }}" data-page="next">›</a>
      </li>
    </ul>
  </div>
@endif


@endsection

@push('scripts')
<script>
// showMessage replaced by toast for all notifications
function showMessage(message, type = 'success') {
  toast(type === 'danger' ? 'Network error' : message, type === 'danger' ? message : '', type === 'danger' ? 'error' : type);
}

// Use SweetAlert2 for toast notifications
function toast(title, message = '', icon = 'success') {
  Swal.fire({
    icon: icon,
    title: title,
    text: message,
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
  });
}

function setLoading(btn, loading) {
  if (!btn) return;
  if (loading) {
    btn.disabled = true;
    btn.dataset.originalText = btn.innerHTML;
    btn.innerHTML = 'Loading...';
  } else {
    btn.disabled = false;
    if (btn.dataset.originalText) btn.innerHTML = btn.dataset.originalText;
  }
}

function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
}

function addToCart(payload, btn) {
  setLoading(btn, true);
  fetch('/api/cart/add-to-cart', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
    },
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      toast(data.message, '', 'success');
    } else if (data.errors) {
      toast('Validation error', Object.values(data.errors).join(', '), 'error');
    } else {
      toast('Error', data.error || 'Failed to add to cart', 'error');
    }
  })
  .catch(() => showMessage('Network error', 'danger'))
  .catch(() => toast('Network error', '', 'error'))
  .finally(() => setLoading(btn, false));
}

function buyNow(payload, btn) {
  setLoading(btn, true);
  fetch('/api/cart/buy-now', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken(),
    },
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      toast('Purchase successful!', '', 'success');
      // Optionally redirect to checkout or order page
    } else if (data.errors) {
      toast('Validation error', Object.values(data.errors).join(', '), 'error');
    } else {
      toast('Error', data.error || 'Failed to buy now', 'error');
    }
  })
  .catch(() => showMessage('Network error', 'danger'))
  .catch(() => toast('Network error', '', 'error'))
  .finally(() => setLoading(btn, false));
}

$(document).on('click', '.pagination a', function(e) {
  var href = $(this).attr('href');
  if (href && href !== '#') {
    window.location.href = href;
  }
});
</script>
@endpush
