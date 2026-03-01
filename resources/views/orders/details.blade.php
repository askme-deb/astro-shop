@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container mb-5 mt-5">
  <div class="row">
    <!-- Sidebar -->
    @include('partials.account-sidebar')
    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 p-4">
      <!-- Order Progress Bar -->
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
              <h4 class="fw-bold mb-1 text-secondary">Order #ORD1023</h4>
              <span class="text-muted"><i class="bi bi-calendar-event"></i> 05 Feb 2026</span>
            </div>
            <span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i>Delivered</span>
          </div>
          <div class="row g-4 align-items-center">
            <!-- Multiple products in order -->
            <div class="col-md-3 col-6">
              <div class="flipkart-product-card">
                <img src="/assets/images/product-1.jpg" alt="Product" class="img-fluid rounded border shadow-sm flipkart-product-img">
              </div>
            </div>
            <div class="col-md-9 col-12">
              <div class="fw-semibold fs-5 mb-1"><i class="bi bi-gem me-1 text-warning"></i>Diamond Ring</div>
              <div class="text-muted mb-2">Qty: <span class="fw-bold">1</span></div>
              <div class="fw-bold fs-4 mb-2 text-success">₹4,999</div>
              <div class="mb-2">
                <span class="text-success"><i class="bi bi-truck me-1"></i> Delivered on <b>07 Feb 2026</b></span>
              </div>
              <div class="mb-2">
                <span class="text-muted"><i class="bi bi-shop-window me-1"></i>Sold by: <b>Astro Jewels Pvt Ltd</b></span>
              </div>
              <div class="mb-2">
                <span class="text-muted"><i class="bi bi-calendar-check me-1"></i>Order placed: <b>05 Feb 2026</b></span>
              </div>
              <div class="mb-2">
                <span class="text-muted"><i class="bi bi-credit-card me-1"></i>Payment: <b>Prepaid (Credit Card)</b></span>
              </div>
              <!-- Expand/Collapse Delivery Address -->
              <div class="mb-2">
                <span class="text-muted"><i class="bi bi-geo-alt me-1"></i>Delivery Address:</span>
                <button class="btn btn-link btn-sm p-0 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#addressCollapse" aria-expanded="false" aria-controls="addressCollapse">Show/Hide</button>
                <div class="collapse mt-2" id="addressCollapse">
                  <div class="border rounded p-2 bg-light flipkart-address">
                    <b>John Doe</b><br>
                    123 Main Street,<br>
                    Mumbai, Maharashtra - 400001<br>
                    <span class="text-muted">Phone: +91-9876543210</span>
                  </div>
                </div>
              </div>
            </div>
            <!-- Second product in order -->
            <div class="col-md-3 col-6">
              <div class="flipkart-product-card">
                <img src="/assets/images/product-3.jpg" alt="Product" class="img-fluid rounded border shadow-sm flipkart-product-img">
              </div>
            </div>
            <div class="col-md-9 col-12">
              <div class="fw-semibold fs-5 mb-1"><i class="bi bi-gem me-1 text-info"></i>Silver Bracelet</div>
              <div class="text-muted mb-2">Qty: <span class="fw-bold">2</span></div>
              <div class="fw-bold fs-4 mb-2 text-success">₹1,299</div>
              <div class="mb-2">
                <span class="text-success"><i class="bi bi-truck me-1"></i> Delivered on <b>07 Feb 2026</b></span>
              </div>
              <div class="mb-2">
                <span class="text-muted"><i class="bi bi-shop-window me-1"></i>Sold by: <b>Astro Jewels Pvt Ltd</b></span>
              </div>
              <div class="mb-2">
                <span class="text-muted"><i class="bi bi-calendar-check me-1"></i>Order placed: <b>05 Feb 2026</b></span>
              </div>
              <div class="mb-2">
                <span class="text-muted"><i class="bi bi-credit-card me-1"></i>Payment: <b>Prepaid (Credit Card)</b></span>
              </div>
              <!-- Expand/Collapse Delivery Address (reuse same address) -->
              <div class="mb-2">
                <span class="text-muted"><i class="bi bi-geo-alt me-1"></i>Delivery Address:</span>
                <button class="btn btn-link btn-sm p-0 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#addressCollapse2" aria-expanded="false" aria-controls="addressCollapse2">Show/Hide</button>
                <div class="collapse mt-2" id="addressCollapse2">
                  <div class="border rounded p-2 bg-light flipkart-address">
                    <b>John Doe</b><br>
                    123 Main Street,<br>
                    Mumbai, Maharashtra - 400001<br>
                    <span class="text-muted">Phone: +91-9876543210</span>
                  </div>
                </div>
              </div>
            </div>
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
      <!-- Timeline Card with Collapse and Tooltips -->
      <div class="card shadow-sm border-0 flipkart-card">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-clock-history me-2"></i>Order Timeline</h5>
            <button class="btn btn-link btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#timelineCollapse" aria-expanded="true" aria-controls="timelineCollapse">Show/Hide</button>
          </div>
          <div class="collapse show" id="timelineCollapse">
            <ul class="timeline">
              <li class="timeline-item completed" data-bs-toggle="tooltip" data-bs-placement="right" title="Order was placed successfully">
                <span class="timeline-status bg-secondary"></span>
                <div class="timeline-content">
                  <span class="fw-bold text-dark"><i class="bi bi-bag-check me-1 text-dark"></i>Order Placed</span>
                  <span class="text-muted ms-2">05 Feb 2026, 10:30 AM</span>
                </div>
              </li>
              <li class="timeline-item completed" data-bs-toggle="tooltip" data-bs-placement="right" title="Order shipped by seller">
                <span class="timeline-status bg-secondary"></span>
                <div class="timeline-content">
                  <span class="fw-bold text-dark"><i class="bi bi-box-seam me-1"></i>Shipped</span>
                  <span class="text-muted ms-2">06 Feb 2026, 2:00 PM</span>
                </div>
              </li>
              <li class="timeline-item completed" data-bs-toggle="tooltip" data-bs-placement="right" title="Order is out for delivery">
                <span class="timeline-status bg-secondary"></span>
                <div class="timeline-content">
                  <span class="fw-bold text-dark"><i class="bi bi-truck me-1"></i>Out for Delivery</span>
                  <span class="text-muted ms-2">07 Feb 2026, 9:00 AM</span>
                </div>
              </li>
              <li class="timeline-item completed" data-bs-toggle="tooltip" data-bs-placement="right" title="Order delivered to customer">
                <span class="timeline-status bg-secondary"></span>
                <div class="timeline-content">
                  <span class="fw-bold text-dark"><i class="bi bi-check-circle me-1"></i>Delivered</span>
                  <span class="text-muted ms-2">07 Feb 2026, 2:30 PM</span>
                </div>
              </li>
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
                <div class="d-flex align-items-center mb-2">
                  <span class="badge bg-primary me-2">Shipped</span>
                  <span class="text-muted">06 Feb 2026, 2:00 PM</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                  <span class="badge bg-warning text-dark me-2">Out for Delivery</span>
                  <span class="text-muted">07 Feb 2026, 9:00 AM</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                  <span class="badge bg-success me-2">Delivered</span>
                  <span class="text-muted">07 Feb 2026, 2:30 PM</span>
                </div>
                <div class="progress mt-3" style="height: 8px;">
                  <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
              </div>
              <div class="alert alert-info mt-3" role="alert">
                Your order has been delivered. Thank you for shopping with us!
              </div>
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
