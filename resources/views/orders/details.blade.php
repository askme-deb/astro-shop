@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container mb-5 mt-5">
  <div class="row">
    <!-- Sidebar -->
    @include('partials.account-sidebar')
    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 p-4">
      @if(!empty($error))
        <div class="alert alert-danger">{{ $error }}</div>
      @elseif(empty($orderDetails))
        <div class="alert alert-info">Order details not found.</div>
      @else
      <!-- Order Progress Bar (static for now) -->
      <div class="mb-4">
        <div class="order-progress-bar bg-light p-3 rounded shadow-sm mb-2">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold text-dark"><i class="bi bi-bag-check me-1"></i>Placed</span>
            <span class="fw-bold text-dark"><i class="bi bi-box-seam me-1"></i>Shipped</span>
            <span class="fw-bold text-dark"><i class="bi bi-truck me-1"></i>Out for Delivery</span>
            <span class="fw-bold text-dark"><i class="bi bi-check-circle me-1"></i>Delivered</span>
          </div>
          <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-secondary" style="width: 100%"></div>
          </div>
        </div>
      </div>
      <!-- Order Summary Card -->
      <div class="card shadow-sm border-0 mb-4 flipkart-card flipkart-hover">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h4 class="fw-bold mb-1 text-secondary">Order #{{ $orderDetails['order_number'] ?? $orderDetails['id'] ?? '-' }}</h4>
              <span class="text-muted"><i class="bi bi-calendar-event"></i> {{ isset($orderDetails['created_at']) ? \Carbon\Carbon::parse($orderDetails['created_at'])->format('d M Y') : '-' }}</span>
            </div>
            @php
              $status = strtolower($orderDetails['order_status'] ?? $orderDetails['status'] ?? '');
              $badgeClass = 'bg-secondary';
              if($status === 'completed' || $status === 'delivered' || $status === 'success') $badgeClass = 'bg-success';
              elseif($status === 'pending' || $status === 'processing') $badgeClass = 'bg-warning text-dark';
              elseif($status === 'cancelled' || $status === 'canceled') $badgeClass = 'bg-danger';
            @endphp
            <span class="badge {{ $badgeClass }} px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i>{{ ucfirst($status) }}</span>
          </div>
          <div class="row g-4 align-items-center">
            @foreach($orderDetails['items'] ?? [] as $item)
            <div class="col-md-3 col-6">
              <div class="flipkart-product-card">
                <img src="{{ $item['product']['image_url'] ?? '/assets/images/product-default.jpg' }}" alt="Product" class="img-fluid rounded border shadow-sm flipkart-product-img">
              </div>
            </div>
            <div class="col-md-9 col-12">
              <div class="fw-semibold fs-5 mb-1">{{ $item['product_name'] ?? ($item['product']['name'] ?? '-') }}</div>
              <div class="text-muted mb-2">Qty: <span class="fw-bold">{{ $item['quantity'] ?? 1 }}</span></div>
              <div class="fw-bold fs-4 mb-2 text-success">₹{{ number_format($item['price'] ?? $item['product']['price'] ?? 0, 2) }}</div>
              <div class="mb-2">
                <span class="text-muted"><i class="bi bi-shop-window me-1"></i>Sold by: <b>{{ $orderDetails['seller_name'] ?? 'Astro Jewels Pvt Ltd' }}</b></span>
              </div>
              <div class="mb-2">
                <span class="text-muted"><i class="bi bi-calendar-check me-1"></i>Order placed: <b>{{ isset($orderDetails['created_at']) ? \Carbon\Carbon::parse($orderDetails['created_at'])->format('d M Y') : '-' }}</b></span>
              </div>
              <div class="mb-2">
                <span class="text-muted"><i class="bi bi-credit-card me-1"></i>Payment: <b>{{ ucfirst($orderDetails['payment_method'] ?? '-') }}</b></span>
              </div>
              <!-- Expand/Collapse Delivery Address -->
              <div class="mb-2">
                <span class="text-muted"><i class="bi bi-geo-alt me-1"></i>Delivery Address:</span>
                <button class="btn btn-link btn-sm p-0 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#addressCollapse{{ $item['id'] }}" aria-expanded="false" aria-controls="addressCollapse{{ $item['id'] }}">Show/Hide</button>
                <div class="collapse mt-2" id="addressCollapse{{ $item['id'] }}">
                  <div class="border rounded p-2 bg-light flipkart-address">
                    <b>{{ $orderDetails['address_book']['shipping_first_name'] ?? 'N/A' }} {{ $orderDetails['address_book']['shipping_last_name'] ?? '' }}</b><br>
                    {{ $orderDetails['address_book']['shipping_address'] ?? 'N/A' }}<br>
                    {{ $orderDetails['address_book']['shipping_city']['name'] ?? ($orderDetails['address_book']['shipping_city'] ?? '') }}, {{ $orderDetails['address_book']['shipping_state']['name'] ?? ($orderDetails['address_book']['shipping_state'] ?? '') }} - {{ $orderDetails['address_book']['shipping_zip_code'] ?? '' }}<br>
                    <span class="text-muted">Phone: {{ $orderDetails['address_book']['shipping_phone_number'] ?? 'N/A' }}</span>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <hr class="my-4">
          <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="#" class="btn btn-theme px-4"><i class="bi bi-repeat"></i> Buy Again</a>
            <button type="button" class="btn btn-outline-theme px-4" data-bs-toggle="modal" data-bs-target="#helpModal"><i class="bi bi-chat-dots"></i> Need Help?</button>
            <button type="button" class="btn btn-outline-theme px-4" data-bs-toggle="modal" data-bs-target="#trackModal"><i class="bi bi-geo me-1"></i> Track Order</button>
            <button type="button" class="btn btn-outline-theme px-4" id="downloadInvoiceBtn"><i class="bi bi-file-earmark-arrow-down me-1"></i> Download Invoice</button>
          </div>
          <!-- Product Rating & Review -->
          <div class="card mt-4 mb-2 shadow-sm border-0">
            <div class="card-body">
              <h6 class="fw-bold mb-2"><i class="bi bi-star-half me-1 text-warning"></i>Rate & Review Product</h6>
              <form id="reviewForm">
                <div class="mb-2">
                  <span class="star-rating">
                    <i class="bi bi-star" data-value="1"></i>
                    <i class="bi bi-star" data-value="2"></i>
                    <i class="bi bi-star" data-value="3"></i>
                    <i class="bi bi-star" data-value="4"></i>
                    <i class="bi bi-star" data-value="5"></i>
                  </span>
                </div>
                <div class="mb-2">
                  <textarea class="form-control" id="reviewComment" rows="2" placeholder="Write your review..."></textarea>
                </div>
                <button type="submit" class="btn btn-theme btn-sm">Submit Review</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      @endif
      <!-- Timeline Card with Collapse and Tooltips -->
      <div class="card shadow-sm border-0 flipkart-card">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-clock-history me-2"></i>Order Timeline</h5>
            <button class="btn btn-link btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#timelineCollapse" aria-expanded="true" aria-controls="timelineCollapse">Show/Hide</button>
          </div>
          <div class="collapse show" id="timelineCollapse">
            <ul class="timeline">
              @php
                $statusMap = [
                  'pending' => ['icon' => 'bi-bag-check', 'label' => 'Order Placed'],
                  'processing' => ['icon' => 'bi-box-seam', 'label' => 'Processing'],
                  'shipped' => ['icon' => 'bi-truck', 'label' => 'Shipped'],
                  'delivered' => ['icon' => 'bi-check-circle', 'label' => 'Delivered'],
                  'cancelled' => ['icon' => 'bi-x-circle', 'label' => 'Cancelled'],
                  'returned' => ['icon' => 'bi-arrow-counterclockwise', 'label' => 'Returned'],
                ];
                $history = [];
                if (!empty($orderTimeline['status_history']) && is_array($orderTimeline['status_history'])) {
                  $history = $orderTimeline['status_history'];
                } elseif (!empty($orderDetails['status_history']) && is_array($orderDetails['status_history'])) {
                  $history = $orderDetails['status_history'];
                }
              @endphp

              @if(!empty($history) && is_array($history))
                @foreach($history as $event)
                  @php
                    $status = strtolower($event['status'] ?? '');
                    $icon = $statusMap[$status]['icon'] ?? 'bi-clock';
                    $label = $statusMap[$status]['label'] ?? ucfirst($status);
                    $completed = ($event['completed'] ?? false) ? 'completed' : '';
                    $active = ($event['active'] ?? false) ? 'active' : '';
                  @endphp
                  @if($active)
                    <li class="timeline-item active" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $label }}">
                      <span class="timeline-status bg-theme"></span>
                      <div class="timeline-content">
                        <span class="fw-bold text-dark"><i class="bi {{ $icon }} me-1 text-dark"></i>{{ $label }}</span>
                        <span class="text-muted ms-2">
                          @if(!empty($event['date']))
                            {{ \Carbon\Carbon::parse($event['date'])->format('d M Y, h:i A') }}
                          @endif
                        </span>
                      </div>
                    </li>
                  @else
                    <li class="timeline-item disabled" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $label }}" style="opacity:0.5;">
                      <span class="timeline-status bg-secondary"></span>
                      <div class="timeline-content">
                        <span class="fw-bold text-muted"><i class="bi {{ $icon }} me-1"></i>{{ $label }}</span>
                        {{-- Optionally show date if available: <span class="text-muted ms-2">{{ $event['date'] ?? '' }}</span> --}}
                      </div>
                    </li>
                  @endif
                @endforeach
              @else
                <li class="timeline-item" data-bs-toggle="tooltip" data-bs-placement="right" title="No timeline data available">
                  <span class="timeline-status bg-secondary"></span>
                  <div class="timeline-content">
                    <span class="fw-bold text-dark"><i class="bi bi-clock me-1 text-dark"></i>No timeline data</span>
                  </div>
                </li>
                <pre style="color:red;">DEBUG: No status_history found. orderDetails: {{ var_export($orderDetails, true) }}</pre>
              @endif
            </ul>
          </div>
        </div>
      </div>
      <!-- Modals for Help and Track Order -->
      <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="helpModalLabel"><i class="bi bi-chat-dots me-2"></i>Need Help?</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form>
                <div class="mb-3">
                  <label for="helpMessage" class="form-label">Your Message</label>
                  <textarea class="form-control" id="helpMessage" rows="3" placeholder="Describe your issue..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="trackModal" tabindex="-1" aria-labelledby="trackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="trackModalLabel"><i class="bi bi-geo me-2"></i>Track Order</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                @php
                  $statusBadgeMap = [
                    'pending' => 'bg-secondary',
                    'processing' => 'bg-warning text-dark',
                    'shipped' => 'bg-primary',
                    'out for delivery' => 'bg-warning text-dark',
                    'delivered' => 'bg-success',
                    'cancelled' => 'bg-danger',
                    'canceled' => 'bg-danger',
                    'returned' => 'bg-info',
                  ];
                  $history = [];
                  if (!empty($orderTimeline['status_history']) && is_array($orderTimeline['status_history'])) {
                    $history = $orderTimeline['status_history'];
                  } elseif (!empty($orderDetails['status_history']) && is_array($orderDetails['status_history'])) {
                    $history = $orderDetails['status_history'];
                  }
                @endphp
                @if(!empty($history) && is_array($history))
                  @foreach($history as $event)
                    @php
                      $status = strtolower($event['status'] ?? '');
                      $label = $statusMap[$status]['label'] ?? ucfirst($status);
                      $badge = $statusBadgeMap[$status] ?? 'bg-secondary';
                    @endphp
                    <div class="d-flex align-items-center mb-2">
                      <span class="badge {{ $badge }} me-2">{{ $label }}</span>
                      <span class="text-muted">
                        @if(!empty($event['date']))
                          {{ \Carbon\Carbon::parse($event['date'])->format('d M Y, h:i A') }}
                        @endif
                      </span>
                    </div>
                  @endforeach
                  <div class="progress mt-3" style="height: 8px;">
                    @php
                      // Calculate progress percentage based on completed events
                      $total = count($history);
                      $completed = 0;
                      foreach($history as $ev) {
                        if (!empty($ev['completed'])) $completed++;
                      }
                      $progress = $total > 0 ? intval(($completed / $total) * 100) : 0;
                      // If last event is active or delivered, set to 100%
                      if (!empty($history[$total-1]['active']) || (isset($history[$total-1]['status']) && strtolower($history[$total-1]['status']) === 'delivered')) {
                        $progress = 100;
                      }
                      $progressBarClass = ($progress === 100) ? 'bg-success' : 'bg-primary';
                    @endphp
                    <div class="progress-bar {{ $progressBarClass }}" style="width: {{ $progress }}%"></div>
                  </div>
                @else
                  <div class="text-muted">No tracking data available.</div>
                @endif
              </div>
              @if(!empty($history) && is_array($history) && isset($history[$total-1]) && (strtolower($history[$total-1]['status'] ?? '') === 'delivered'))
                {{-- <div class="alert alert-info mt-3" role="alert">
                  Your order has been delivered. Thank you for shopping with us!
                </div> --}}
              @endif
            </div>
          </div>
        </div>
      </div>
      <!-- Toast Notification -->
      <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
        <div id="reviewToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">
              Thank you! Your review has been submitted.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
      <!-- Custom Flipkart Styles & Interactivity -->
      <style>
        .text-theme {
          color: #f88400 !important;
        }
        .bg-theme{
            background-color: #f88400 !important;
        }
        body {
          background: #f5f7fa;
        }
        .btn-theme {
          background-color: #f88400;
          color: #fff;
          border: 1px solid #f88400;
        }
        .btn-theme:hover, .btn-theme:focus {
          background-color: #d86e00;
          color: #fff;
          border-color: #d86e00;
        }
        .btn-outline-theme {
          background: #fff;
          color: #f88400;
          border: 1.5px solid #f88400;
        }
        .btn-outline-theme:hover, .btn-outline-theme:focus {
          background: #f88400;
          color: #fff;
          border-color: #d86e00;
        }
        .flipkart-card {
          border-radius: 14px;
          transition: box-shadow 0.2s;
        }
        .flipkart-hover:hover {
          box-shadow: 0 8px 32px rgba(13,110,253,0.12);
        }
        .flipkart-product-card {
          background: #fff;
          border-radius: 10px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.07);
          padding: 10px;
          transition: box-shadow 0.2s;
        }
        .flipkart-product-card:hover {
          box-shadow: 0 4px 16px rgba(13,110,253,0.13);
        }
        .flipkart-product-img {
          max-height: 140px;
          object-fit: contain;
          background: #f7f7f7;
        }
        .flipkart-address {
          font-size: 0.97rem;
        }
        .order-progress-bar {
          background: linear-gradient(90deg,#e3f0ff 0%,#f7f7f7 100%);
        }
        .progress-bar.bg-primary {
          background: linear-gradient(90deg,#0d6efd 60%,#00c853 100%);
        }
        .star-rating i {
          font-size: 1.5rem;
          color: #e0e0e0;
          cursor: pointer;
          transition: color 0.2s;
        }
        .star-rating .selected {
          color: #ffc107;
        }
        .timeline {
          list-style: none;
          padding-left: 0;
          position: relative;
        }
        .timeline::before {
          content: '';
          position: absolute;
          left: 18px;
          top: 0;
          width: 2px;
          height: 100%;
          background: #e0e0e0;
        }
        .timeline-item {
          position: relative;
          margin-bottom: 2rem;
          padding-left: 40px;
        }
        .timeline-status {
          position: absolute;
          left: 10px;
          top: 6px;
          width: 16px;
          height: 16px;
          border-radius: 50%;
          background: #e0e0e0;
          border: 2px solid #fff;
        }
        .timeline-item.completed .timeline-status {
          background: #0d6efd;
          box-shadow: 0 0 0 2px #fff;
        }
        .timeline-content {
          display: flex;
          align-items: center;
        }
        .timeline-content .fw-bold {
          font-size: 1rem;
          color: #0d6efd;
        }
        hr.my-4 {
          border-top: 2px solid #e3f0ff;
        }
        @media (max-width: 768px) {
          .timeline-item {
            padding-left: 30px;
          }
          .flipkart-product-img {
            max-height: 90px;
          }
        }
      </style>
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          // Tooltips
          var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
          tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
          });
          // Star rating
          var stars = document.querySelectorAll('.star-rating i');
          var selectedRating = 0;
          stars.forEach(function(star) {
            star.addEventListener('mouseover', function() {
              var val = parseInt(star.getAttribute('data-value'));
              stars.forEach(function(s, i) {
                s.classList.toggle('selected', i < val);
              });
            });
            star.addEventListener('mouseout', function() {
              stars.forEach(function(s, i) {
                s.classList.toggle('selected', i < selectedRating);
              });
            });
            star.addEventListener('click', function() {
              selectedRating = parseInt(star.getAttribute('data-value'));
              stars.forEach(function(s, i) {
                s.classList.toggle('selected', i < selectedRating);
              });
            });
          });
          // Review form
          var reviewForm = document.getElementById('reviewForm');
          if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
              e.preventDefault();
              var toastEl = document.getElementById('reviewToast');
              var toast = new bootstrap.Toast(toastEl);
              toast.show();
              reviewForm.reset();
              selectedRating = 0;
              stars.forEach(function(s) { s.classList.remove('selected'); });
            });
          }
          // Download Invoice
          var downloadBtn = document.getElementById('downloadInvoiceBtn');
          if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
              var link = document.createElement('a');
              link.href = '#';
              link.download = 'invoice-ORD1023.pdf';
              link.click();
            });
          }
        });
      </script>
    </div>
  </div>
</div>
@endsection
