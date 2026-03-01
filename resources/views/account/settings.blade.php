@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="container mb-5 mt-5">
  <div class="row">
    @include('partials.account-sidebar')
    <div class="col-md-9 col-lg-10 p-4">
      <div class="card p-4  border-1" style="border-radius: 0px;">
        <h5 class="fw-bold mb-3">Account Settings</h5>
        <div class="settings-list">
          <div class="settings-item mb-4 p-3 border rounded-3 shadow-sm bg-white">
            <div class="d-flex align-items-center">
              <i class="bi bi-person-circle fs-2 text-primary me-3"></i>
              <div class="flex-grow-1">
                <div class="fw-semibold">Profile</div>
                <div class="text-muted small">Edit your personal information</div>
              </div>
              <a href="{{ route('dashboard') }}" class="btn btn-outline-dark btn-sm ">Manage</a>
            </div>
          </div>
          <div class="settings-item mb-4 p-3 border rounded-3 shadow-sm bg-white">
            <div class="d-flex align-items-center">
              <i class="bi bi-shield-lock fs-2 text-success me-3"></i>
              <div class="flex-grow-1">
                <div class="fw-semibold">Security</div>
                <div class="text-muted small">Change your password and security settings</div>
              </div>
              <a href="#" class="btn btn-outline-dark btn-sm ">Manage</a>
            </div>
          </div>
          <div class="settings-item mb-4 p-3 border rounded-3 shadow-sm bg-white">
            <div class="d-flex align-items-center">
              <i class="bi bi-bell fs-2 text-warning me-3"></i>
              <div class="flex-grow-1">
                <div class="fw-semibold">Notifications</div>
                <div class="text-muted small">Manage notification preferences</div>
              </div>
              <a href="#" class="btn btn-outline-dark btn-sm ">Manage</a>
            </div>
          </div>
          <div class="settings-item mb-4 p-3 border rounded-3 shadow-sm bg-white">
            <div class="d-flex align-items-center">
              <i class="bi bi-trash fs-2 text-danger me-3"></i>
              <div class="flex-grow-1">
                <div class="fw-semibold">Delete Account</div>
                <div class="text-muted small">Remove your account permanently</div>
              </div>
              <a href="#" class="btn btn-outline-danger btn-sm ">Delete</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  .settings-list .settings-item {
    transition: box-shadow 0.2s, border-color 0.2s;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
  }
  .settings-list .settings-item:hover {
    box-shadow: 0 6px 32px rgba(13,110,253,0.10);
    border-color: #0d6efd;
  }
  .settings-item .btn {
    border-radius: 18px;
    font-size: 1rem;
    font-weight: 500;
    padding: 0.35em 1.2em;
  }
  .card {
    border-radius: 18px;
  }
</style>
@endsection
