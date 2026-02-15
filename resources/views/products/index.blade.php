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
            <button class="btn btn-cart" onclick="addToCart()">Add to Cart</button>
            <button class="btn btn-buy" onclick="buyNow('{{ $product['name'] ?? 'Product' }}')">Buy Now</button>
          </div>
        </div>
      </div>
    @empty
      <p>No products found.</p>
    @endforelse
  </div>

  <!-- <div class="row g-4 d-none d-md-flex">

   
    <div class="col-md-3 col-sm-6">
      <div class="product-card">
        <i class="bi bi-heart wishlist"></i>
        <img src="images/product-1.jpg">
        <div class="rating">⭐ 4.8 | 316</div>
        <h6 class="mt-2">Rose Gold Princess Earrings</h6>
        <div>
          <span class="price">₹3,499</span>
          <span class="old-price ms-2">₹5,799</span>
        </div>
        <div class="offer">EXTRA 16% OFF with coupon</div>
        <div class="d-grid gap-2 mt-3">
          <button class="btn btn-cart" onclick="addToCart()">Add to Cart</button>
          <button class="btn btn-buy" onclick="buyNow('Rose Gold Princess Earrings')">Buy Now</button>
        </div>
      </div>
    </div>

  
    <div class="col-md-3 col-sm-6">
      <div class="product-card">
        <i class="bi bi-heart wishlist"></i>
        <img src="images/product-1.jpg">
        <div class="rating">⭐ 4.8 | 217</div>
        <h6 class="mt-2">Anushka Sharma Rose Gold Bracelet</h6>
        <div>
          <span class="price">₹6,499</span>
          <span class="old-price ms-2">₹12,999</span>
        </div>
        <div class="offer">EXTRA 20% OFF with coupon</div>
        <div class="d-grid gap-2 mt-3">
          <button class="btn btn-cart" onclick="addToCart()">Add to Cart</button>
          <button class="btn btn-buy" onclick="buyNow('Rose Gold Bracelet')">Buy Now</button>
        </div>
      </div>
    </div>

    
    <div class="col-md-3 col-sm-6">
      <div class="product-card">
        <i class="bi bi-heart wishlist"></i>
        <img src="images/product-1.jpg">
        <div class="rating">⭐ 4.7 | 203</div>
        <h6 class="mt-2">Silver Zircon Love Island Ring</h6>
        <div>
          <span class="price">₹1,899</span>
          <span class="old-price ms-2">₹3,299</span>
          <div class="offer">&nbsp;</div>
        </div>
        <div class="d-grid gap-2 mt-3">
          <button class="btn btn-cart" onclick="addToCart()">Add to Cart</button>
          <button class="btn btn-buy" onclick="buyNow('Silver Zircon Ring')">Buy Now</button>
        </div>
      </div>
    </div>

  
    <div class="col-md-3 col-sm-6">
      <div class="product-card">
        <i class="bi bi-heart wishlist"></i>
        <img src="images/product-1.jpg">
        <div class="rating">⭐ 4.8 | 244</div>
        <h6 class="mt-2">Oxidised Silver Moonstone Pendant</h6>
        <div>
          <span class="price">₹3,799</span>
          <span class="old-price ms-2">₹5,999</span>
        </div>
        <div class="offer">EXTRA 16% OFF with coupon</div>
        <div class="d-grid gap-2 mt-3">
          <button class="btn btn-cart" onclick="addToCart()">Add to Cart</button>
          <button class="btn btn-buy" onclick="buyNow('Moonstone Pendant')">Buy Now</button>
        </div>
      </div>
    </div>

  </div> -->

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
