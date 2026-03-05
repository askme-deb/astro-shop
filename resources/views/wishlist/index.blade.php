@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="container mb-5 mt-5">
  <div class="row">

    <!-- Sidebar -->
    @include('partials.account-sidebar')

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 p-4">
      <!-- Wishlist -->
      <div id="wishlist" class="section">
        <div class="card shadow-sm p-4 border-0 mb-4 flipkart-card flipkart-hover">
          <h5 class="fw-bold mb-3">Wishlist</h5>
          <div class="wishlist-list">
            @if(empty($wishlist) || count($wishlist) === 0)
              <div class="text-center text-muted py-5">Your wishlist is empty.</div>
            @else
              @foreach($wishlist as $item)
                @php $product = $item['product'] ?? null; @endphp
                <div class="wishlist-item mb-4 p-3 border rounded shadow-sm d-flex align-items-center bg-white position-relative">
                  <img src="{{ $product['image_url'] ?? '/assets/images/no-image.png' }}" alt="{{ $product['name'] ?? 'Product' }}" style="width: 70px; height: 70px; object-fit: cover;" class="rounded me-3 border">
                  <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $product['name'] ?? 'Product' }}</div>
                    <div class="text-muted small">₹{{ number_format($product['price'] ?? 0) }}</div>
                    <div class="mt-2">
                      @if(isset($product['stock']) && $product['stock'] > 0)
                        <span class="badge bg-light text-dark">In Stock</span>
                      @elseif(isset($product['stock']))
                        <span class="badge bg-warning text-dark">Out of Stock</span>
                      @endif
                      {{-- Optionally show rating if available --}}
                    </div>
                  </div>
                  <a href="javascript:void(0)" onclick="addToCart({{ json_encode(['product_id' => $product['id'] ?? 0, 'quantity' => 1]) }}, this)" class="btn btn-outline-dark btn-sm me-2 px-3"><i class="bi bi-cart-plus"></i> Add to Cart</a>
                  <button class="btn btn-sm btn-outline-danger px-3" onclick="removeWishlistItem({{ $item['id'] }}, this)"><i class="bi bi-trash"></i> Remove</button>
                  @push('scripts')
                  <script>
                  function removeWishlistItem(wishlistId, btn) {
                    if (!confirm('Remove this item from your wishlist?')) return;
                    btn.disabled = true;
                    fetch("{{ route('wishlist.remove') }}", {
                      method: 'POST',
                      headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                      },
                      body: JSON.stringify({ wishlist_id: wishlistId })
                    })
                    .then(res => res.json())
                    .then(data => {
                      if (data.success) {
                        // Remove the wishlist item from the DOM
                        let itemDiv = btn.closest('.wishlist-item');
                        if (itemDiv) itemDiv.remove();
                        // Optionally show a toast or alert
                      } else {
                        alert(data.message || 'Failed to remove item.');
                        btn.disabled = false;
                      }
                    })
                    .catch(() => {
                      alert('Failed to remove item.');
                      btn.disabled = false;
                    });
                  }
                  </script>
                  @endpush
                  <span class="wishlist-action position-absolute top-0 end-0 m-2">
                    <a href="javascript:void(0)" class="btn btn-link btn-sm text-primary"><i class="bi bi-eye"></i></a>
                  </span>
                </div>
              @endforeach
            @endif
          </div>
        </div>
      </div>
      <style>
        .wishlist-list .wishlist-item {
          transition: box-shadow 0.2s, border-color 0.2s;
          background: #fff;
        }
        .wishlist-list .wishlist-item:hover {
          box-shadow: 0 4px 24px rgba(0,0,0,0.10);
          border-color: #0d6efd;
        }
        .wishlist-item img {
          border: 1px solid #eee;
          box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .btn-outline-dark {
          border-width: 2px;
        }
        .wishlist-item .fw-semibold {
          font-size: 1rem;
        }
        .wishlist-item .badge {
          font-size: 0.95rem;
          padding: 0.4em 1em;
        }
        .wishlist-action {
          z-index: 2;
        }
        @media (max-width: 768px) {
          .wishlist-item {
            flex-direction: column;
            align-items: flex-start;
          }
          .wishlist-item img {
            margin-bottom: 1rem;
          }
        }
      </style>
    </div>
  </div>
</div>
@endsection
