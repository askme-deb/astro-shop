{{-- DEBUG: If you see this, Blade rendering works! --}}
@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="container mb-5 mt-5">
  <div class="row">
    @include('partials.account-sidebar')
    <div class="col-md-9 col-lg-10 p-4">
      <div class="card shadow-sm border-0 p-4 mb-4 flipkart-card flipkart-hover" style="border-radius: 0px;">
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
              <button class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#securityModal">Manage</button>
            </div>
          </div>
          <!-- Security Modal -->
          <div class="modal fade" id="securityModal" tabindex="-1" aria-labelledby="securityModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="securityModalLabel">Security Settings</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="securityForm">
                    <div class="mb-3">
                      <label class="form-label">Current Password</label>
                      <input type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">New Password</label>
                      <input type="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Enable Two-Factor Authentication</label>
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="toggle2FA">
                        <label class="form-check-label" for="toggle2FA">2FA</label>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Recent Login Activity</label>
                      <ul class="list-group">
                        <li class="list-group-item">02 Mar 2026, 10:15 AM - Chrome, Mumbai</li>
                        <li class="list-group-item">01 Mar 2026, 08:42 PM - Mobile, Pune</li>
                      </ul>
                    </div>
                    <button type="submit" class="btn btn-theme">Save Changes</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="settings-item mb-4 p-3 border rounded-3 shadow-sm bg-white">
            <div class="d-flex align-items-center">
              <i class="bi bi-bell fs-2 text-warning me-3"></i>
              <div class="flex-grow-1">
                <div class="fw-semibold">Notifications</div>
                <div class="text-muted small">Manage notification preferences</div>
              </div>
              <button class="btn btn-outline-dark btn-sm" data-bs-toggle="modal" data-bs-target="#notificationsModal">Manage</button>
            </div>
          </div>
          <!-- Notifications Modal -->
          <div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="notificationsModalLabel">Notification Preferences</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="notificationsForm">
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" id="notifyEmail" checked>
                      <label class="form-check-label" for="notifyEmail">Email Notifications</label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" id="notifySMS">
                      <label class="form-check-label" for="notifySMS">SMS Notifications</label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input" type="checkbox" id="notifyPush">
                      <label class="form-check-label" for="notifyPush">Push Notifications</label>
                    </div>
                    <button type="submit" class="btn btn-theme">Save Preferences</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="settings-item mb-4 p-3 border rounded-3 shadow-sm bg-white">
            <div class="d-flex align-items-center">
              <i class="bi bi-trash fs-2 text-danger me-3"></i>
              <div class="flex-grow-1">
                <div class="fw-semibold">Delete Account</div>
                <div class="text-muted small">Remove your account permanently</div>
              </div>
              <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete</button>
            </div>
          </div>
          <!-- Delete Account Modal -->
          <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="deleteAccountModalLabel">Confirm Account Deletion</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p class="mb-3 text-danger">Are you sure you want to delete your account? This action cannot be undone.</p>
                  <form id="deleteAccountForm">
                    <div class="mb-3">
                      <label class="form-label">Enter your password to confirm</label>
                      <input type="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
      <!-- Toast Notification -->
      <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div id="settingsToast" class="toast align-items-center text-white bg-theme border-0" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">Action completed successfully!</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
      <style>
        .bg-theme { background-color: #f88400 !important; }
        .btn-theme { background-color: #f88400; color: #fff; border: none; }
        .btn-theme:hover, .btn-theme:focus { background-color: #e67600; color: #fff; }
      </style>
      <script>
        // Toast Notification
        function showSettingsToast(msg) {
          var toastEl = document.getElementById('settingsToast');
          toastEl.querySelector('.toast-body').textContent = msg;
          var toast = new bootstrap.Toast(toastEl);
          toast.show();
        }
        document.getElementById('securityForm').addEventListener('submit', function(e) {
          e.preventDefault();
          showSettingsToast('Security settings updated!');
          bootstrap.Modal.getInstance(document.getElementById('securityModal')).hide();
        });
        document.getElementById('notificationsForm').addEventListener('submit', function(e) {
          e.preventDefault();
          showSettingsToast('Notification preferences saved!');
          bootstrap.Modal.getInstance(document.getElementById('notificationsModal')).hide();
        });
        document.getElementById('deleteAccountForm').addEventListener('submit', function(e) {
          e.preventDefault();
          showSettingsToast('Account deleted (demo only).');
          bootstrap.Modal.getInstance(document.getElementById('deleteAccountModal')).hide();
        });
      </script>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
