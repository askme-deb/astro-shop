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
            @if(!empty($error))
                <div class="alert alert-danger">{{ $error }}</div>
            @elseif($orders->isEmpty())
                <div class="alert alert-info">No orders found.</div>
            @else
                @foreach($orders as $order)
                  <div class="order-item mb-4 p-3 border rounded shadow-sm position-relative bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <span class="fw-bold">Order #{{ $order['order_number'] ?? $order['id'] ?? '-' }}</span>
                        <span class="ms-3 text-muted"><i class="bi bi-calendar"></i> {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y') : '-' }}</span>
                      </div>
                      @php
                        $status = strtolower($order['order_status'] ?? $order['status'] ?? '');
                        $badgeClass = 'bg-secondary';
                        if($status === 'completed' || $status === 'delivered' || $status === 'success') $badgeClass = 'bg-success';
                        elseif($status === 'pending' || $status === 'processing') $badgeClass = 'bg-warning text-dark';
                        elseif($status === 'cancelled' || $status === 'canceled') $badgeClass = 'bg-danger';
                      @endphp
                      <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="order-products">
                      <div class="fw-semibold mb-2">Total: ₹{{ number_format($order['total_amount'] ?? 0, 2) }}</div>
                      <div class="text-muted small">Payment: {{ ucfirst($order['payment_method'] ?? '-') }} | Status: {{ ucfirst($order['payment_status'] ?? '-') }}</div>
                    </div>
                    <a href="{{ route('orders.details', ['order' => $order['order_number'] ?? $order['id'] ?? 0]) }}" class="btn btn-outline-primary btn-sm ms-3 px-4">View Details</a>
                    <div class="order-actions position-absolute end-0 bottom-0 p-2">
                      <a href="#" class="btn btn-link btn-sm text-primary"><i class="bi bi-repeat"></i> Buy Again</a>
                      <a href="#" class="btn btn-link btn-sm text-danger"><i class="bi bi-chat-dots"></i> Need Help?</a>
                    </div>
                  </div>
                @endforeach
                @if(method_exists($orders, 'links'))
                    <div class="mt-3">{!! $orders->links() !!}</div>
                @endif
            @endif
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
          border-width: 1px;
          color: #212529;
          border-color: #f98800;
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
