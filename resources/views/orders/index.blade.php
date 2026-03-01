@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container mb-5 mt-5">
  <div class="row">

    <!-- Sidebar -->
    @include('partials.account-sidebar')

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 p-4">
      <!-- Orders -->
      <div id="orders" class="section">
        <div class="card shadow-sm p-4 border-0 mb-4 flipkart-card flipkart-hover">
          <h5 class="fw-bold mb-3">Order History</h5>
          <div class="order-list">
            <!-- Order Item (multiple products per order) -->
            <div class="order-item mb-4 p-3 border rounded shadow-sm position-relative bg-white">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <span class="fw-bold">Order #ORD1023</span>
                  <span class="ms-3 text-muted"><i class="bi bi-calendar"></i> 05 Feb 2026</span>
                </div>
                <span class="badge bg-success">Delivered</span>
              </div>
              <hr class="my-2">
              <!-- Multiple items in order -->
              <div class="order-products">
                <div class="d-flex align-items-center mb-3">
                  <img src="/assets/images/product-1.jpg" alt="Product" style="width: 70px; height: 70px; object-fit: cover;" class="rounded me-3 border">
                  <div class="flex-grow-1">
                    <div class="fw-semibold">Diamond Ring</div>
                    <div class="text-muted small">Qty: 1</div>
                    <div class="fw-bold mt-1">₹4,999</div>
                    <div class="mt-2">
                      <span class="text-success"><i class="bi bi-truck"></i> Delivered on 07 Feb 2026</span>
                    </div>
                  </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                  <img src="/assets/images/product-3.jpg" alt="Product" style="width: 70px; height: 70px; object-fit: cover;" class="rounded me-3 border">
                  <div class="flex-grow-1">
                    <div class="fw-semibold">Silver Bracelet</div>
                    <div class="text-muted small">Qty: 2</div>
                    <div class="fw-bold mt-1">₹1,299</div>
                    <div class="mt-2">
                      <span class="text-success"><i class="bi bi-truck"></i> Delivered on 07 Feb 2026</span>
                    </div>
                  </div>
                </div>
              </div>
              <a href="{{ route('orders.details', ['order' => 'ORD1023']) }}" class="btn btn-outline-primary btn-sm ms-3 px-4">View Details</a>
              <div class="order-actions position-absolute end-0 bottom-0 p-2">
                <a href="#" class="btn btn-link btn-sm text-primary"><i class="bi bi-repeat"></i> Buy Again</a>
                <a href="#" class="btn btn-link btn-sm text-danger"><i class="bi bi-chat-dots"></i> Need Help?</a>
              </div>
            </div>
            <!-- Order Item (multiple products per order) -->
            <div class="order-item mb-4 p-3 border rounded shadow-sm position-relative bg-white">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <span class="fw-bold">Order #ORD1024</span>
                  <span class="ms-3 text-muted"><i class="bi bi-calendar"></i> 10 Feb 2026</span>
                </div>
                <span class="badge bg-warning text-dark">Processing</span>
              </div>
              <hr class="my-2">
              <!-- Multiple items in order -->
              <div class="order-products">
                <div class="d-flex align-items-center mb-3">
                  <img src="/assets/images/product-2.jpg" alt="Product" style="width: 70px; height: 70px; object-fit: cover;" class="rounded me-3 border">
                  <div class="flex-grow-1">
                    <div class="fw-semibold">Gold Necklace</div>
                    <div class="text-muted small">Qty: 1</div>
                    <div class="fw-bold mt-1">₹2,499</div>
                    <div class="mt-2">
                      <span class="text-warning"><i class="bi bi-clock-history"></i> Expected by 15 Feb 2026</span>
                    </div>
                  </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                  <img src="/assets/images/product-4.jpg" alt="Product" style="width: 70px; height: 70px; object-fit: cover;" class="rounded me-3 border">
                  <div class="flex-grow-1">
                    <div class="fw-semibold">Pearl Earrings</div>
                    <div class="text-muted small">Qty: 3</div>
                    <div class="fw-bold mt-1">₹999</div>
                    <div class="mt-2">
                      <span class="text-warning"><i class="bi bi-clock-history"></i> Expected by 15 Feb 2026</span>
                    </div>
                  </div>
                </div>
              </div>
              <a href="{{ route('orders.details', ['order' => 'ORD1024']) }}" class="btn btn-outline-primary btn-sm ms-3 px-4">View Details</a>
              <div class="order-actions position-absolute end-0 bottom-0 p-2">
                <a href="#" class="btn btn-link btn-sm text-primary"><i class="bi bi-repeat"></i> Buy Again</a>
                <a href="#" class="btn btn-link btn-sm text-danger"><i class="bi bi-chat-dots"></i> Need Help?</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <style>
        .order-list .order-item {
          transition: box-shadow 0.2s, border-color 0.2s;
          background: #fff;
        }
        .order-list .order-item:hover {
          box-shadow: 0 4px 24px rgba(0,0,0,0.10);
          border-color: #0d6efd;
        }
        .order-actions {
          display: flex;
          gap: 0.5rem;
        }
        .order-item .badge {
          font-size: 0.95rem;
          padding: 0.5em 1em;
        }
        .order-item img {
          border: 1px solid #eee;
          box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .btn-outline-primary {
          border-width: 2px;
          color: #212529;
          border-color: #212529;
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus {
          background: #212529;
          color: #fff;
          border-color: #212529;
        }
        .order-item .fw-bold {
          font-size: 1.1rem;
        }
        .order-item .fw-semibold {
          font-size: 1rem;
        }
        @media (max-width: 768px) {
          .order-item {
            flex-direction: column;
          }
        }
      </style>
    </div>
  </div>
</div>
@endsection
