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
        <div class="card p-4">
          <h5 class="fw-bold mb-3">Wishlist</h5>
          <div class="wishlist-list">
            <!-- Wishlist Item -->
            <div class="wishlist-item mb-4 p-3 border rounded shadow-sm d-flex align-items-center bg-white position-relative">
              <img src="/assets/images/product-1.jpg" alt="Diamond Ring" style="width: 70px; height: 70px; object-fit: cover;" class="rounded me-3 border">
              <div class="flex-grow-1">
                <div class="fw-semibold">Diamond Ring</div>
                <div class="text-muted small">₹4,999</div>
                <div class="mt-2">
                  <span class="badge bg-light text-dark">In Stock</span>
                  <span class="text-success ms-2"><i class="bi bi-star-fill"></i> 4.8</span>
                </div>
              </div>
              <a href="#" class="btn btn-outline-dark btn-sm me-2 px-3"><i class="bi bi-cart-plus"></i> Add to Cart</a>
              <button class="btn btn-sm btn-outline-danger px-3"><i class="bi bi-trash"></i> Remove</button>
              <span class="wishlist-action position-absolute top-0 end-0 m-2">
                <a href="#" class="btn btn-link btn-sm text-primary"><i class="bi bi-eye"></i></a>
              </span>
            </div>
            <!-- Wishlist Item -->
            <div class="wishlist-item mb-4 p-3 border rounded shadow-sm d-flex align-items-center bg-white position-relative">
              <img src="/assets/images/product-2.jpg" alt="Gold Necklace" style="width: 70px; height: 70px; object-fit: cover;" class="rounded me-3 border">
              <div class="flex-grow-1">
                <div class="fw-semibold">Gold Necklace</div>
                <div class="text-muted small">₹2,499</div>
                <div class="mt-2">
                  <span class="badge bg-light text-dark">Only 2 left</span>
                  <span class="text-warning ms-2"><i class="bi bi-star-fill"></i> 4.6</span>
                </div>
              </div>
              <a href="#" class="btn btn-outline-dark btn-sm me-2 px-3"><i class="bi bi-cart-plus"></i> Add to Cart</a>
              <button class="btn btn-sm btn-outline-danger px-3"><i class="bi bi-trash"></i> Remove</button>
              <span class="wishlist-action position-absolute top-0 end-0 m-2">
                <a href="#" class="btn btn-link btn-sm text-primary"><i class="bi bi-eye"></i></a>
              </span>
            </div>
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
