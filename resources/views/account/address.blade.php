@extends('layouts.app')

@section('title', 'My Address')

@section('content')
<div class="container mb-5 mt-5">
  <div class="row">
    @include('partials.account-sidebar')
    <div class="col-md-9 col-lg-10 p-4">
      <div class="card p-4 border-1" style="border-radius: 0px;">
        <div class="d-flex align-items-center mb-4">
          <i class="bi bi-geo-alt fs-2 text-primary me-3"></i>
          <h4 class="fw-bold mb-0">Saved Addresses</h4>
          <a href="#" class="btn btn-outline-dark btn-sm ms-auto px-4 rounded-pill"><i class="bi bi-plus-lg"></i> Add New Address</a>
        </div>
        <hr>
        <div class="address-list">
          <!-- Address Item -->
          <div class="address-item mb-4 p-4 border rounded-3 shadow-sm bg-white position-relative">
            <div class="d-flex align-items-center mb-2">
              <span class="fw-bold me-2 fs-5">John Doe</span>
              <span class="badge bg-primary text-white ms-2">Home</span>
              <span class="ms-auto text-muted"><i class="bi bi-telephone"></i> +91 9876543210</span>
            </div>
            <div class="text-muted mb-2 fs-6"><i class="bi bi-geo-alt-fill text-primary"></i> 221B Baker Street, Mumbai, Maharashtra, 400001</div>
            <div class="d-flex gap-2 mt-2">
              <a href="#" class="btn btn-outline-primary btn-sm rounded-pill"><i class="bi bi-pencil"></i> Edit</a>
              <a href="#" class="btn btn-outline-danger btn-sm rounded-pill"><i class="bi bi-trash"></i> Delete</a>
              <a href="#" class="btn btn-outline-dark btn-sm rounded-pill"><i class="bi bi-check-circle"></i> Set as Default</a>
            </div>
            <span class="position-absolute top-0 end-0 m-2"><i class="bi bi-star-fill text-warning fs-4"></i></span>
          </div>
          <!-- Address Item -->
          <div class="address-item mb-4 p-4 border rounded-3 shadow-sm bg-white position-relative">
            <div class="d-flex align-items-center mb-2">
              <span class="fw-bold me-2 fs-5">Jane Smith</span>
              <span class="badge bg-secondary text-white ms-2">Office</span>
              <span class="ms-auto text-muted"><i class="bi bi-telephone"></i> +91 9123456789</span>
            </div>
            <div class="text-muted mb-2 fs-6"><i class="bi bi-geo-alt-fill text-primary"></i> 123 Business Park, Pune, Maharashtra, 411001</div>
            <div class="d-flex gap-2 mt-2">
              <a href="#" class="btn btn-outline-primary btn-sm rounded-pill"><i class="bi bi-pencil"></i> Edit</a>
              <a href="#" class="btn btn-outline-danger btn-sm rounded-pill"><i class="bi bi-trash"></i> Delete</a>
              <a href="#" class="btn btn-outline-dark btn-sm rounded-pill"><i class="bi bi-check-circle"></i> Set as Default</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  .address-list .address-item {
    transition: box-shadow 0.2s, border-color 0.2s;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
  }
  .address-list .address-item:hover {
    box-shadow: 0 6px 32px rgba(13,110,253,0.10);
    border-color: #0d6efd;
  }
  .address-item .badge {
    font-size: 0.98rem;
    padding: 0.45em 1.1em;
    border-radius: 12px;
  }
  .address-item .btn {
    border-radius: 18px;
    font-size: 1rem;
    font-weight: 500;
    padding: 0.35em 1.2em;
  }
  .address-item .bi-star-fill {
    font-size: 1.4rem;
  }
  .card {
    border-radius: 18px;
  }
  .address-list .fs-5 {
    font-size: 1.15rem !important;
  }
  .address-list .fs-6 {
    font-size: 1rem !important;
  }
</style>
@endsection
