@extends('layouts.app')

@section('title', 'Products')

@section('content')
<!-- Page Title -->

<section class="py-4 border-bottom inner_warp" aria-labelledby="products-heading">
  <div class="container">
    @if(!empty($category))
      <h2 id="products-heading" class="fw-bold mb-2">
        {{ __(ucfirst(str_replace('-', ' ', $category))) }}
      </h2>
    @else
      <h2 id="products-heading" class="fw-bold mb-2">{{ __('Shop') }}</h2>
    @endif
    @if(!empty($products))
      <p class="text-muted">
        {{ trans_choice('Showing :count product|Showing :count products', count($products), ['count' => count($products)]) }}
      </p>
    @else
      <p class="text-muted">{{ __('No products available at the moment.') }}</p>
    @endif
  </div>
</section>
 <!-- Page Title -->


@php
$priceValues = collect($products ?? [])
  ->map(fn ($product) => (float) data_get($product, 'price', 0))
  ->filter(fn ($price) => $price > 0)
  ->values();

$priceMinBound = $priceValues->isNotEmpty() ? (int) floor($priceValues->min() / 1000) * 1000 : 0;
$priceMaxBound = $priceValues->isNotEmpty() ? (int) ceil($priceValues->max() / 1000) * 1000 : 100000;

if ($priceMaxBound <= $priceMinBound) {
  $priceMaxBound = $priceMinBound + 10000;
}

$priceStep = max((int) ceil(($priceMaxBound - $priceMinBound) / 100), 100);
$selectedMinPrice = max((int) request('min_price', $priceMinBound), $priceMinBound);
$selectedMaxPrice = min((int) request('max_price', $priceMaxBound), $priceMaxBound);

$selectedPriceBounds = [
  'min' => min($selectedMinPrice, $selectedMaxPrice),
  'max' => max($selectedMinPrice, $selectedMaxPrice),
];

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
    'name' => 'price_range',
    'label' => 'Price',
    'type' => 'price-range',
    'min_name' => 'min_price',
    'max_name' => 'max_price',
    'min' => $priceMinBound,
    'max' => $priceMaxBound,
    'step' => $priceStep,
    'selected_min' => $selectedPriceBounds['min'],
    'selected_max' => $selectedPriceBounds['max'],
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
    'name' => 'in_stock',
    'label' => 'In Stock',
    'type' => 'checkbox',
    'options' => [
      ['value' => '1', 'label' => 'In Stock Only'],
    ],
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
<x-product-filters :filters="$filters" :sort-options="$sortOptions" :selected-sort="request('sort')" />


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

<div class="pagination-wrapper" data-current-page="{{ $currentPage }}" data-last-page="{{ $lastPage }}">
  @if($lastPage > 1)
    <ul class="pagination">
      {{-- Previous page --}}
      <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
        <a href="#" data-page="{{ max($currentPage - 1, 1) }}">‹</a>
      </li>

      {{-- Page numbers --}}
      @for($page = 1; $page <= $lastPage; $page++)
        <li class="page-item {{ $page === $currentPage ? 'active' : '' }}">
          <a href="#" data-page="{{ $page }}">{{ $page }}</a>
        </li>
      @endfor

      {{-- Next page --}}
      <li class="page-item {{ $currentPage >= $lastPage ? 'disabled' : '' }}">
        <a href="#" data-page="{{ min($currentPage + 1, $lastPage) }}">›</a>
      </li>
    </ul>
  @endif
</div>


@endsection

@push('scripts')
<script>
const currentCategorySlug = @json($category ?? request('category'));

function getSelectedFilters() {
  const filters = {};
  // Collect text/number input filters
  document.querySelectorAll('.filter-input-field').forEach(input => {
    if (input.value) {
      filters[input.name] = [input.value];
    }
  });
  // Collect select filters
  document.querySelectorAll('.filter-select-field').forEach(select => {
    if (select.value) {
      filters[select.name] = [select.value];
    }
  });
  // Collect checkbox filters
  document.querySelectorAll('.filter-checkbox-field').forEach(checkbox => {
    if (checkbox.checked && checkbox.value) {
      filters[checkbox.name] = [checkbox.value];
    }
  });
  document.querySelectorAll('.filter-price-range').forEach(range => {
    const minName = range.dataset.minName;
    const maxName = range.dataset.maxName;
    const minDefault = Number(range.dataset.minDefault);
    const maxDefault = Number(range.dataset.maxDefault);
    const minValue = Number(range.dataset.minValue);
    const maxValue = Number(range.dataset.maxValue);

    if (minName && minValue > minDefault) {
      filters[minName] = [String(minValue)];
    }

    if (maxName && maxValue < maxDefault) {
      filters[maxName] = [String(maxValue)];
    }
  });
  return filters;
}

function getSortValue() {
  return document.getElementById('sortSelect')?.value || '';
}

function formatPriceRangeValue(value) {
  return new Intl.NumberFormat('en-IN').format(Number(value) || 0);
}

function renderProductSkeletons(count = 8) {
  return Array.from({ length: count }, () => `
    <div class="col-md-3 col-sm-6">
      <div class="product-card product-card-skeleton" aria-hidden="true">
        <div class="skeleton-block skeleton-image"></div>
        <div class="skeleton-block skeleton-chip"></div>
        <div class="skeleton-block skeleton-title"></div>
        <div class="skeleton-block skeleton-price"></div>
        <div class="skeleton-block skeleton-offer"></div>
        <div class="skeleton-actions">
          <div class="skeleton-block skeleton-button"></div>
          <div class="skeleton-block skeleton-button"></div>
        </div>
      </div>
    </div>
  `).join('');
}

function renderEmptyProductsState() {
  return `
    <div class="col-12">
      <div class="product-empty-state">No products found.</div>
    </div>
  `;
}

function buildPaginationItems(currentPage, lastPage) {
  const items = [];

  if (lastPage <= 7) {
    for (let page = 1; page <= lastPage; page += 1) {
      items.push(page);
    }

    return items;
  }

  items.push(1);

  if (currentPage > 3) {
    items.push('ellipsis-start');
  }

  const start = Math.max(2, currentPage - 1);
  const end = Math.min(lastPage - 1, currentPage + 1);

  for (let page = start; page <= end; page += 1) {
    items.push(page);
  }

  if (currentPage < lastPage - 2) {
    items.push('ellipsis-end');
  }

  items.push(lastPage);

  return items;
}

function renderPagination(pagination = {}, requestedPage = 1) {
  const wrapper = document.querySelector('.pagination-wrapper');

  if (!wrapper) {
    return;
  }

  const currentPage = Math.max(Number(pagination.current_page || requestedPage || 1), 1);
  const lastPage = Math.max(Number(pagination.last_page || currentPage || 1), 1);

  wrapper.dataset.currentPage = String(currentPage);
  wrapper.dataset.lastPage = String(lastPage);

  if (lastPage <= 1) {
    wrapper.innerHTML = '';
    return;
  }

  const items = buildPaginationItems(currentPage, lastPage);
  const prevPage = Math.max(currentPage - 1, 1);
  const nextPage = Math.min(currentPage + 1, lastPage);

  wrapper.innerHTML = `
    <ul class="pagination">
      <li class="page-item ${currentPage <= 1 ? 'disabled' : ''}">
        <a href="#" data-page="${prevPage}" aria-label="Previous page">‹</a>
      </li>
      ${items.map(item => {
        if (typeof item !== 'number') {
          return '<li class="page-item page-item-ellipsis"><span>…</span></li>';
        }

        return `
          <li class="page-item ${item === currentPage ? 'active' : ''}">
            <a href="#" data-page="${item}" aria-label="Page ${item}">${item}</a>
          </li>
        `;
      }).join('')}
      <li class="page-item ${currentPage >= lastPage ? 'disabled' : ''}">
        <a href="#" data-page="${nextPage}" aria-label="Next page">›</a>
      </li>
    </ul>
  `;
}

function syncPriceRange(range) {
  const minSlider = range.querySelector('.filter-range-slider-min');
  const maxSlider = range.querySelector('.filter-range-slider-max');
  const minInput = range.querySelector('.filter-price-hidden-min');
  const maxInput = range.querySelector('.filter-price-hidden-max');
  const display = range.querySelector('.filter-price-range-values');
  const progress = range.querySelector('.filter-price-range-progress');
  const minBound = Number(range.dataset.minDefault);
  const maxBound = Number(range.dataset.maxDefault);
  const step = Number(minSlider?.step || 1);

  if (!minSlider || !maxSlider || !minInput || !maxInput || !display || !progress) {
    return;
  }

  let minValue = Number(minSlider.value);
  let maxValue = Number(maxSlider.value);

  if (minValue > maxValue - step) {
    minValue = maxValue - step;
  }

  if (maxValue < minValue + step) {
    maxValue = minValue + step;
  }

  minValue = Math.max(minBound, minValue);
  maxValue = Math.min(maxBound, maxValue);

  minSlider.value = String(minValue);
  maxSlider.value = String(maxValue);
  minInput.value = String(minValue);
  maxInput.value = String(maxValue);
  range.dataset.minValue = String(minValue);
  range.dataset.maxValue = String(maxValue);

  const left = ((minValue - minBound) / (maxBound - minBound)) * 100;
  const right = ((maxValue - minBound) / (maxBound - minBound)) * 100;
  progress.style.left = `${left}%`;
  progress.style.width = `${Math.max(right - left, 0)}%`;
  display.textContent = `Rs ${formatPriceRangeValue(minValue)} - Rs ${formatPriceRangeValue(maxValue)}`;
}

let fetchProductsTimer;
let activeProductsRequestId = 0;

function scheduleFetchProducts() {
  window.clearTimeout(fetchProductsTimer);
  fetchProductsTimer = window.setTimeout(() => fetchProducts(), 180);
}

function fetchProducts(page = 1) {
  const container = document.querySelector('.product_warp2 .row');
  const paginationWrapper = document.querySelector('.pagination-wrapper');
  const filters = getSelectedFilters();
  const sort = getSortValue();
  const params = new URLSearchParams();
  const requestId = ++activeProductsRequestId;

  if (container) {
    container.classList.add('is-loading');
    container.setAttribute('aria-busy', 'true');
    container.innerHTML = renderProductSkeletons();
  }

  if (paginationWrapper) {
    paginationWrapper.classList.add('is-loading');
  }

  Object.entries(filters).forEach(([key, values]) => {
    values.forEach(val => params.append(key, val));
  });
  if (sort) params.append('sort', sort);
  if (currentCategorySlug) params.append('category', currentCategorySlug);
  params.append('page', page);
  fetch(`/ajax/products/search?${params.toString()}`)
    .then(res => res.json())
    .then(data => {
      if (requestId !== activeProductsRequestId) {
        return;
      }

      if (container) {
        const products = Array.isArray(data.products) ? data.products : [];
        container.innerHTML = products.length
          ? products.map(product => renderProductCard(product)).join('')
          : renderEmptyProductsState();
      }
      renderPagination(data.pagination || {}, page);
      // Render a product card in JS (matches Blade component as much as possible)
      function renderProductCard(product) {
        // Build the product show URL using the slug (assumes route is /products/{slug})
        const productUrl = `/products/${encodeURIComponent(product.slug)}`;
        const productPayload = JSON.stringify({ product_id: product.id || 0, quantity: 1 });
        return `
          <div class="col-md-3 col-sm-6">
            <div class="product-card">
              <a href="${productUrl}">
                <img src="${product.image_url || '/assets/images/product-1.jpg'}" alt="${product.name || 'Product'}">
              </a>
              <div class="rating">⭐</div>
              <h6 class="mt-2">${product.name || 'Product'}</h6>
              <div>
                <span class="price">₹${product.price || '0.00'}</span>
              </div>
              <div class="offer">&nbsp;</div>
              <div class="d-grid gap-2 mt-3">
                <button class="btn btn-cart" onclick='addToCart(${productPayload}, this)'>Add to Cart</button>
                <button class="btn btn-buy" onclick='buyNow(${productPayload}, this)'>Buy Now</button>
              </div>
            </div>
          </div>
        `;
      }
      // TODO: update pagination if needed
    })
    .catch(() => {
      if (requestId !== activeProductsRequestId || !container) {
        return;
      }

      container.innerHTML = `
        <div class="col-12">
          <div class="product-empty-state">Unable to load products right now.</div>
        </div>
      `;
    })
    .finally(() => {
      if (requestId !== activeProductsRequestId) {
        return;
      }

      if (container) {
        container.classList.remove('is-loading');
        container.removeAttribute('aria-busy');
      }

      if (paginationWrapper) {
        paginationWrapper.classList.remove('is-loading');
      }
    });
}

document.addEventListener('DOMContentLoaded', function() {
  renderPagination({
    current_page: Number(document.querySelector('.pagination-wrapper')?.dataset.currentPage || 1),
    last_page: Number(document.querySelector('.pagination-wrapper')?.dataset.lastPage || 1),
  });

  document.querySelectorAll('.filter-input-field').forEach(input => {
    input.addEventListener('input', function() {
      fetchProducts();
    });
  });
  document.querySelectorAll('.filter-select-field').forEach(select => {
    select.addEventListener('change', function() {
      fetchProducts();
    });
  });
  document.querySelectorAll('.filter-checkbox-field').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      fetchProducts();
    });
  });
  document.querySelectorAll('.filter-price-range').forEach(range => {
    const minSlider = range.querySelector('.filter-range-slider-min');
    const maxSlider = range.querySelector('.filter-range-slider-max');

    syncPriceRange(range);

    minSlider?.addEventListener('input', function() {
      syncPriceRange(range);
      scheduleFetchProducts();
    });

    maxSlider?.addEventListener('input', function() {
      syncPriceRange(range);
      scheduleFetchProducts();
    });
  });
  document.getElementById('sortSelect')?.addEventListener('change', function() {
    fetchProducts();
  });

  document.querySelector('.filter-reset-link')?.addEventListener('click', function() {
    document.querySelectorAll('.filter-input-field').forEach(input => {
      input.value = '';
    });

    document.querySelectorAll('.filter-select-field').forEach(select => {
      select.selectedIndex = 0;
    });

    document.querySelectorAll('.filter-checkbox-field').forEach(checkbox => {
      checkbox.checked = false;
    });

    document.querySelectorAll('.filter-price-range').forEach(range => {
      const minDefault = Number(range.dataset.minDefault);
      const maxDefault = Number(range.dataset.maxDefault);
      const minSlider = range.querySelector('.filter-range-slider-min');
      const maxSlider = range.querySelector('.filter-range-slider-max');

      if (minSlider) {
        minSlider.value = String(minDefault);
      }

      if (maxSlider) {
        maxSlider.value = String(maxDefault);
      }

      syncPriceRange(range);
    });

    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect && sortSelect.options.length) {
      sortSelect.selectedIndex = 0;
    }

    fetchProducts();
  });

  document.querySelector('.pagination-wrapper')?.addEventListener('click', function(event) {
    const link = event.target.closest('a[data-page]');

    if (!link || link.closest('.page-item.disabled')) {
      return;
    }

    event.preventDefault();

    const page = Number(link.dataset.page || 1);
    if (page > 0) {
      fetchProducts(page);
      document.querySelector('.product_warp2')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});
</script>
@endpush
