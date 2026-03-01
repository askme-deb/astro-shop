@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mb-5 mt-5">
  <div class="row">
    @include('partials.account-sidebar')
    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 p-4">

      <!-- Profile -->
      <div id="profile" class="section">
        <div class="card shadow-sm border-0 mb-4 p-4 flipkart-card flipkart-hover">
          <div class="d-flex align-items-center mb-4">
            <div class="position-relative me-4">
              <img id="profileAvatar" src="/assets/images/profile-avatar.png" alt="Profile" style="width: 80px; height: 80px; object-fit: cover;" class="rounded-circle border">
              <label for="avatarUpload" class="position-absolute bottom-0 end-0 bg-theme rounded-circle p-1" style="cursor:pointer;">
                <i class="bi bi-pencil-square text-white"></i>
                <input type="file" id="avatarUpload" accept="image/*" style="display:none;">
              </label>
            </div>
            <div>
              <h5 class="fw-bold mb-1">John Doe</h5>
              <span class="text-muted">+91 9876543210</span>
            </div>
            <a href="#" class="btn btn-outline-dark btn-sm ms-auto px-4">Edit Profile</a>
          </div>
         
          <form class="profile-form">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="fw-semibold mb-1">Full Name</label>
                <input type="text" class="form-control" value="John Doe">
              </div>
              <div class="col-md-6">
                <label class="fw-semibold mb-1">Email Address</label>
                <input type="email" class="form-control" value="john@email.com">
              </div>
              <div class="col-md-6">
                <label class="fw-semibold mb-1">Mobile Number</label>
                <input type="text" class="form-control" value="+91 9876543210">
              </div>
              <div class="col-md-6">
                <label class="fw-semibold mb-1">Change Password</label>
                <input type="password" class="form-control" placeholder="New Password">
              </div>
              <div class="col-md-6">
                <label class="fw-semibold mb-1">Gender</label>
                <select class="form-select">
                  <option selected>Male</option>
                  <option>Female</option>
                  <option>Other</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="fw-semibold mb-1">Date of Birth</label>
                <input type="date" class="form-control" value="1990-01-01">
              </div>
              <div class="col-md-6">
                <label class="fw-semibold mb-1">Alternate Mobile</label>
                <input type="text" class="form-control" value="">
              </div>
              <div class="col-md-6">
                <label class="fw-semibold mb-1">Address</label>
                <input type="text" class="form-control" value="221B Baker Street, Mumbai, India">
              </div>
              <div class="col-md-6">
                <label class="fw-semibold mb-1">Pincode</label>
                <input type="text" class="form-control" value="400001">
              </div>
              <div class="col-md-6">
                <label class="fw-semibold mb-1">State</label>
                <input type="text" class="form-control" value="Maharashtra">
              </div>
              <div class="col-md-6">
                <label class="fw-semibold mb-1">City</label>
                <input type="text" class="form-control" value="Mumbai">
              </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
              <button class="btn btn-dark px-4">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
      <style>
        .profile-form label {
          font-size: 0.97rem;
        }
        .profile-form input, .profile-form select {
          font-size: 1rem;
        }
        .profile-form .form-control:focus, .profile-form .form-select:focus {
          border-color: #0d6efd;
          box-shadow: 0 0 0 0.15rem rgba(13,110,253,.15);
        }
        .profile-form .btn-dark {
          font-weight: 500;
        }
        .profile-form .btn-outline-dark {
          border-width: 2px;
        }
      </style>

      <!-- Orders -->
      <div id="orders" class="section d-none">
        <div class="card p-4">
          <h5 class="fw-bold mb-3">Order History</h5>
          <table class="table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#ORD1023</td>
                <td>05 Feb 2026</td>
                <td><span class="badge bg-success">Delivered</span></td>
                <td>₹4,999</td>
              </tr>
              <tr>
                <td>#ORD1024</td>
                <td>10 Feb 2026</td>
                <td><span class="badge bg-warning">Processing</span></td>
                <td>₹2,499</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Wishlist -->
      <div id="wishlist" class="section d-none">
        <div class="card p-4">
          <h5 class="fw-bold mb-3">Wishlist</h5>
          <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between">
              Diamond Ring
              <button class="btn btn-sm btn-outline-danger">Remove</button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              Gold Necklace
              <button class="btn btn-sm btn-outline-danger">Remove</button>
            </li>
          </ul>
        </div>
      </div>

      <!-- Address -->
      <div id="address" class="section d-none">
        <div class="card p-4">
          <h5 class="fw-bold mb-3">Saved Address</h5>
          <textarea class="form-control" rows="4">221B Baker Street, Mumbai, India</textarea>
          <button class="btn btn-dark mt-3">Update Address</button>
        </div>
      </div>

      <!-- Payments -->
      <div id="payments" class="section d-none">
        <div class="card p-4">
          <h5 class="fw-bold mb-3">Payment History</h5>
          <table class="table">
            <thead>
              <tr>
                <th>Transaction ID</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>TXN98765</td>
                <td>UPI</td>
                <td>₹4,999</td>
                <td><span class="badge bg-success">Success</span></td>
              </tr>
              <tr>
                <td>TXN98766</td>
                <td>Card</td>
                <td>₹2,499</td>
                <td><span class="badge bg-success">Success</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

<script>
  document.getElementById('avatarUpload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(ev) {
        document.getElementById('profileAvatar').src = ev.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
</script>
