@extends('layouts.app')

@section('title', 'My Address')

@section('content')
<div class="container mb-5 mt-5">
  <div class="row">
    @include('partials.account-sidebar')
    <div class="col-md-9 col-lg-10 p-4">
      <div class="card p-4 border-0 shadow-sm address-main-card">
        <div class="d-flex align-items-center mb-4">
          <i class="bi bi-geo-alt fs-2 text-theme me-3"></i>
          <h4 class="fw-bold mb-0 text-secondary">Saved Addresses</h4>
          <a href="javascript:void(0)" id="showAddAddressBtn"  class="btn btn-theme btn-sm ms-auto px-4"><i class="bi bi-plus-lg"></i> Add New Address</a>
        </div>
     
 
       
        <!-- Add New Address Form (hidden by default) -->
        <div class="mb-4" id="addAddressFormContainer" style="display:none;">
          <form id="addAddressForm" class="p-4 border rounded-3 shadow-sm bg-white">
            <h5 class="fw-bold mb-3 text-theme"><i class="bi bi-plus-lg me-2"></i>Add New Address</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <label for="newName" class="form-label">Name</label>
                <input type="text" class="form-control" id="newName" required>
              </div>
              <div class="col-md-6">
                <label for="newPhone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="newPhone" required>
              </div>
              <div class="col-12">
                <label for="newAddress" class="form-label">Address</label>
                <textarea class="form-control" id="newAddress" rows="2" required></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-theme">Save Address</button>
              </div>
            </div>
          </form>
        </div>
        <div class="address-list">
          <!-- Address Item -->
          <div class="address-item mb-4 p-4 border rounded-3 shadow-sm bg-white position-relative address-default">
            <div class="d-flex align-items-center mb-2">
              <span class="fw-bold me-2 fs-5 text-theme">John Doe</span>
              <span class="badge bg-theme text-white ms-2">Home</span>
              <span class="ms-auto text-muted"><i class="bi bi-telephone"></i> +91 9876543210</span>
            </div>
            <div class="text-muted mb-2 fs-6"><i class="bi bi-geo-alt-fill text-theme"></i> 221B Baker Street, Mumbai, Maharashtra, 400001</div>
            <div class="d-flex gap-2 mt-2">
                <a href="#" class="btn btn-outline-theme btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                <a href="#" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Delete</a>
                <a href="#" class="btn btn-outline-theme btn-sm"><i class="bi bi-check-circle"></i> Set as Default</a>
            </div>
           
            <span class="position-absolute top-0 start-0 m-2 px-3 py-1 bg-theme text-white rounded-pill small shadow-sm">Default</span>
          </div>
          <!-- Address Item -->
          <div class="address-item mb-4 p-4 border rounded-3 shadow-sm bg-white position-relative">
            <div class="d-flex align-items-center mb-2">
              <span class="fw-bold me-2 fs-5">Jane Smith</span>
              <span class="badge bg-secondary text-white ms-2">Office</span>
              <span class="ms-auto text-muted"><i class="bi bi-telephone"></i> +91 9123456789</span>
            </div>
            <div class="text-muted mb-2 fs-6"><i class="bi bi-geo-alt-fill text-theme"></i> 123 Business Park, Pune, Maharashtra, 411001</div>
            <div class="d-flex gap-2 mt-2">
                <a href="#" class="btn btn-outline-theme btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                <a href="#" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Delete</a>
                <a href="#" class="btn btn-outline-theme btn-sm"><i class="bi bi-check-circle"></i> Set as Default</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  document.getElementById('showAddAddressBtn').addEventListener('click', function() {
    var formContainer = document.getElementById('addAddressFormContainer');
    formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
  });
</script>
<style>
  .address-main-card {
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 4px 24px rgba(248,132,0,0.08);
  }
  .address-list .address-item {
    transition: box-shadow 0.2s, border-color 0.2s;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    border: 2px solid #f3f3f3;
    position: relative;
  }
  .address-list .address-item:hover {
    box-shadow: 0 6px 32px rgba(248,132,0,0.13);
    border-color: #f88400;
  }
  .address-default {
    border: 2px solid #f88400;
    box-shadow: 0 6px 32px rgba(248,132,0,0.13);
  }
  .address-item .badge.bg-theme {
    background: #f88400;
    color: #fff;
  }
  .address-item .btn-theme {
    background-color: #f88400;
    color: #fff;
    border: 1px solid #f88400;
  }
  .address-item .btn-theme:hover, .address-item .btn-theme:focus {
    background-color: #d86e00;
    color: #fff;
    border-color: #d86e00;
  }
  .address-item .btn-outline-theme {
    background: #fff;
    color: #f88400;
    border: 1.5px solid #f88400;
  }
  .address-item .btn-outline-theme:hover, .address-item .btn-outline-theme:focus {
    background: #f88400;
    color: #fff;
    border-color: #d86e00;
  }
  .address-item .badge {
    font-size: 0.98rem;
    padding: 0.45em 1.1em;
    border-radius: 12px;
  }
  .address-item .btn {
    font-size: 1rem;
    font-weight: 500;
    padding: 0.35em 1.2em;
  }
  .address-item .bi-star-fill {
    font-size: 1.4rem;
  }
  .address-list .fs-5 {
    font-size: 1.15rem !important;
  }
  .address-list .fs-6 {
    font-size: 1rem !important;
  }
  .text-theme {
    color: #f88400 !important;
  }
</style>
@endsection
