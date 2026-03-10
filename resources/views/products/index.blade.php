@extends('layouts.app')

@section('title', 'Products')

@section('content')
<!-- Page Title -->

<section class="py-4 border-bottom inner_warp">
  <div class="container">
    <h2 class="fw-bold">Shop</h2>
    @if(!empty($category))
      <h4 class="fw-bold">Category: {{ ucfirst(str_replace('-', ' ', $category)) }}</h4>
    @endif
    @if(!empty($products))
      <p class="text-muted">Showing {{ count($products) }} products</p>
    @else
      <p class="text-muted">No products available at the moment.</p>
    @endif
  </div>
</section>
 <!-- Page Title -->


@php
$filters = [
//   [
//     'name' => 'q',
//     'label' => 'Search',
//     'type' => 'text',
//     'placeholder' => 'Search products...'
//   ],
  [
    'name' => 'category_id',
    'label' => 'Category',
    'options' => [], // Fill dynamically if needed
  ],
 
  [
    'name' => 'min_price',
    'label' => 'Min Price',
    'type' => 'number',
    'placeholder' => 'Min price'
  ],
  [
    'name' => 'max_price',
    'label' => 'Max Price',
    'type' => 'number',
    'placeholder' => 'Max price'
  ],
  [
    'name' => 'in_stock',
    'label' => 'In Stock',
    'options' => [
      ['value' => '1', 'label' => 'In Stock Only'],
    ],
  ],
  [
    'name' => 'ratti',
    'label' => 'Ratti',
    'type' => 'number',
    'placeholder' => 'Ratti'
  ],
  [
    'name' => 'carat',
    'label' => 'Carat',
    'type' => 'number',
    'placeholder' => 'Carat'
  ],
  
  [
    'name' => 'product_grade_id',
    'label' => 'Product Grade',
    'options' => [], // Fill dynamically if needed
  ],
 
  
];
$sortOptions = [
  ['value' => 'best', 'label' => 'Best selling'],
  ['value' => 'new', 'label' => 'New arrivals'],
  ['value' => 'price-low', 'label' => 'Price: Low to High'],
  ['value' => 'price-high', 'label' => 'Price: High to Low'],
];
@endphp
<x-product-filters :filters="$filters" :sort-options="$sortOptions" />


<!-- Products -->
<!-- Products -->
<div class="container my-5 product_warp2">
  <div class="row g-4 d-md-flex">
    @forelse($products ?? [] as $product)
      <x-product-card :product="$product" />
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

function getSelectedFilters() {
  const filters = {};
  // Collect active dropdown filters
  document.querySelectorAll('.filter-menu li.active').forEach(li => {
    const filter = li.getAttribute('data-filter');
    if (!filters[filter]) filters[filter] = [];
    filters[filter].push(li.getAttribute('data-value'));
  });
  // Collect text/number input filters
  document.querySelectorAll('.filter-input-field').forEach(input => {
    if (input.value) {
      filters[input.name] = [input.value];
    }
  });
  return filters;
}

function getSortValue() {
  return document.getElementById('sortSelect')?.value || '';
}

function fetchProducts(page = 1) {
  const filters = getSelectedFilters();
  const sort = getSortValue();
  const params = new URLSearchParams();
  Object.entries(filters).forEach(([key, values]) => {
    values.forEach(val => params.append(key, val));
  });
  if (sort) params.append('sort', sort);
  params.append('page', page);
  fetch(`/ajax/products/search?${params.toString()}`)
    .then(res => res.json())
    .then(data => {
      const container = document.querySelector('.product_warp2 .row');
      if (container) {
        container.innerHTML = data.products.map(product => renderProductCard(product)).join('');
      }
      // Render a product card in JS (matches Blade component as much as possible)
      function renderProductCard(product) {
        return `
          <div class="col-md-3 col-sm-6">
            <div class="product-card">
              <img src="${product.image_url || '/assets/images/product-1.jpg'}" alt="${product.name || 'Product'}">
              <div class="rating">⭐</div>
              <h6 class="mt-2">${product.name || 'Product'}</h6>
              <div>
                <span class="price">₹${product.price || '0.00'}</span>
              </div>
              <div class="offer">&nbsp;</div>
              <div class="d-grid gap-2 mt-3">
                <button class="btn btn-cart">Add to Cart</button>
                <button class="btn btn-buy">Buy Now</button>
              </div>
            </div>
          </div>
        `;
      }
      // TODO: update pagination if needed
    });
}

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.filter-menu li').forEach(li => {
    li.addEventListener('click', function() {
      this.classList.toggle('active');
      fetchProducts();
    });
  });
  document.querySelectorAll('.filter-input-field').forEach(input => {
    input.addEventListener('input', function() {
      fetchProducts();
    });
  });
  document.getElementById('sortSelect')?.addEventListener('change', function() {
    fetchProducts();
  });
});
</script>
@endpush
