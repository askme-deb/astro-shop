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
        <!-- Add/Edit Address Modal -->
        <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form id="addressForm">
                <div class="modal-header">
                  <h5 class="modal-title" id="addressModalLabel">Add/Edit Address</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" id="addressId" name="address_id">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label for="firstName" class="form-label">First Name</label>
                      <input type="text" class="form-control" id="firstName" name="first_name" required>
                    </div>
                    <div class="col-md-6">
                      <label for="lastName" class="form-label">Last Name</label>
                      <input type="text" class="form-control" id="lastName" name="last_name" required>
                    </div>
                    <div class="col-md-6">
                      <label for="phone" class="form-label">Phone</label>
                      <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="col-md-6">
                      <label for="pincode" class="form-label">Pincode</label>
                      <input type="text" class="form-control" id="pincode" name="pincode" required>
                    </div>
                    <div class="col-12">
                      <label for="address" class="form-label">Address</label>
                      <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="col-12">
                      <label for="landmark" class="form-label">Landmark (Optional)</label>
                      <input type="text" class="form-control" id="landmark" name="landmark">
                    </div>
                    <div class="col-md-6">
                      <label for="state" class="form-label">State</label>
                      <select class="form-select" id="state" name="state" required>
                        <option value="">Select State</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label for="city" class="form-label">City</label>
                      <select class="form-select" id="city" name="city" required>
                        <option value="">Select City</option>
                      </select>
                    </div>
                    <div class="col-12">
                      <label class="form-label">Address Type</label>
                      <div>
                        <label class="me-2"><input type="radio" name="address_type" value="home" checked> Home</label>
                        <label class="me-2"><input type="radio" name="address_type" value="office"> Office</label>
                        <label><input type="radio" name="address_type" value="other"> Other</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-theme">Save Address</button>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="address-list">
          <!-- Address Item -->
          {{-- <div class="address-item mb-4 p-4 border rounded-3 shadow-sm bg-white position-relative address-default">
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
          </div> --}}
            @if(!empty($error))
              <div class="alert alert-danger">{{ $error }}</div>
            @endif
            @forelse($addresses as $address)
              <div class="address-item mb-4 p-4 border rounded-3 shadow-sm bg-white position-relative {{ $address['is_default'] ? 'address-default' : '' }}">
                <div class="d-flex align-items-center mb-2">
                  <span class="fw-bold me-2 fs-5 text-theme">{{ $address['full_name'] ?? 'N/A' }}</span>
                  <span class="badge bg-theme text-white ms-2">{{ ucfirst($address['type'] ?? 'Other') }}</span>
                  <span class="ms-auto text-muted"><i class="bi bi-telephone"></i> {{ $address['phone'] ? '+91 ' . $address['phone'] : '' }}</span>
                </div>
                <div class="text-muted mb-2 fs-6"><i class="bi bi-geo-alt-fill text-theme"></i> {{ $address['address_line'] }}, {{ $address['city'] }}, {{ $address['state'] }}, {{ $address['postal_code'] }}</div>
                <div class="d-flex gap-2 mt-2">
                  <button type="button" class="btn btn-outline-theme btn-sm edit-address-btn" data-id="{{ $address['id'] }}"><i class="bi bi-pencil"></i> Edit</button>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-address-btn" data-id="{{ $address['id'] }}"><i class="bi bi-trash"></i> Delete</button>
                  @if(!$address['is_default'])
                  <button type="button" class="btn btn-outline-theme btn-sm set-default-address-btn" data-id="{{ $address['id'] }}"><i class="bi bi-check-circle"></i> Set as Default</button>
                  @endif
                </div>
                @if($address['is_default'])
                  <span class="position-absolute top-0 start-0 m-2 px-3 py-1 bg-theme text-white rounded-pill small shadow-sm">Default</span>
                @endif
              </div>
            @empty
              <div class="alert alert-info">No addresses found.</div>
            @endforelse
        </div>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Show Add Address Modal
  document.getElementById('showAddAddressBtn').addEventListener('click', function() {
    document.getElementById('addressForm').reset();
    document.getElementById('addressId').value = '';
    document.getElementById('addressModalLabel').textContent = 'Add Address';
    var modal = new bootstrap.Modal(document.getElementById('addressModal'));
    modal.show();
  });

  // Edit Address
  document.querySelectorAll('.edit-address-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var id = this.getAttribute('data-id');
      // Fetch address data from addresses array (should be available in JS)
      var address = window.addresses.find(function(a) { return a.id == id; });
      if (address) {
        document.getElementById('addressId').value = address.id;
        document.getElementById('firstName').value = address.raw.shipping_first_name || '';
        document.getElementById('lastName').value = address.raw.shipping_last_name || '';
        document.getElementById('phone').value = address.raw.shipping_phone_number || '';
        document.getElementById('pincode').value = address.raw.shipping_zip_code || '';
        document.getElementById('address').value = address.raw.shipping_address || '';
        document.getElementById('landmark').value = address.raw.landmark || '';
        document.getElementById('state').value = address.raw.shipping_state?.id || address.raw.shipping_state || '';
        // --- State & City Dropdown AJAX ---
        const stateSelect = document.getElementById('state');
        const citySelect = document.getElementById('city');
        function loadStates(selectedStateId = null) {
          stateSelect.innerHTML = '<option value="">Loading...</option>';
          fetch("{{ route('account.address.state-list') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ country_id: 101 })
          })
          .then(res => res.json())
          .then(data => {
            stateSelect.innerHTML = '<option value="">Select State</option>';
            if (data.states && Array.isArray(data.states)) {
              data.states.forEach(state => {
                const opt = document.createElement('option');
                opt.value = state.id;
                opt.textContent = state.name;
                if (selectedStateId && (state.id == selectedStateId)) opt.selected = true;
                stateSelect.appendChild(opt);
              });
            }
          });
        }

        function loadCities(stateId, selectedCityId = null) {
          citySelect.innerHTML = '<option value="">Loading...</option>';
          if (!stateId) {
            citySelect.innerHTML = '<option value="">Select City</option>';
            return;
          }
          fetch("{{ route('account.address.city-list') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ state_id: stateId })
          })
          .then(res => res.json())
          .then(data => {
            citySelect.innerHTML = '<option value="">Select City</option>';
            if (data.cities && Array.isArray(data.cities)) {
              data.cities.forEach(city => {
                const opt = document.createElement('option');
                opt.value = city.id;
                opt.textContent = city.name;
                if (selectedCityId && (city.id == selectedCityId)) opt.selected = true;
                citySelect.appendChild(opt);
              });
            }
          });
        }

        // Initial load for Add Address
        loadStates();
        stateSelect.addEventListener('change', function() {
          loadCities(this.value);
        });

        // When opening modal for edit, pre-select state/city
        document.querySelectorAll('.edit-address-btn').forEach(function(btn) {
          btn.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var address = window.addresses.find(function(a) { return a.id == id; });
            if (address) {
              // Load states and select current
              loadStates(address.raw.shipping_state?.id || address.raw.shipping_state || null);
              // Load cities and select current
              setTimeout(function() {
                loadCities(address.raw.shipping_state?.id || address.raw.shipping_state || null, address.raw.shipping_city?.id || address.raw.shipping_city || null);
              }, 400);
            }
          });
        });

        // When opening modal for add, reset city dropdown
        document.getElementById('showAddAddressBtn').addEventListener('click', function() {
          loadStates();
          citySelect.innerHTML = '<option value="">Select City</option>';
        });
        document.getElementById('city').value = address.raw.shipping_city?.id || address.raw.shipping_city || '';
        document.querySelector('input[name="address_type"][value="' + (address.type || 'home') + '"]').checked = true;
        document.getElementById('addressModalLabel').textContent = 'Edit Address';
        var modal = new bootstrap.Modal(document.getElementById('addressModal'));
        modal.show();
      }
    });
  });

  // Save Address (Add/Edit)
  document.getElementById('addressForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var id = formData.get('address_id');
    var url = id ? '{{ route('account.address.update', ['id' => 'ADDRESS_ID']) }}'.replace('ADDRESS_ID', id) : '{{ route('account.address.save') }}';
    fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.status) {
        location.reload();
      } else {
        alert(data.message || 'Failed to save address.');
      }
    })
    .catch(() => alert('Failed to save address.'));
  });

  // Delete Address
  document.querySelectorAll('.delete-address-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      if (!confirm('Are you sure you want to delete this address?')) return;
      var id = this.getAttribute('data-id');
      fetch('{{ route('account.address.delete', ['id' => 'ADDRESS_ID']) }}'.replace('ADDRESS_ID', id), {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.status) {
          location.reload();
        } else {
          alert(data.message || 'Failed to delete address.');
        }
      })
      .catch(() => alert('Failed to delete address.'));
    });
  });

  // Set Default Address
  document.querySelectorAll('.set-default-address-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var id = this.getAttribute('data-id');
      fetch('{{ route('account.address.default', ['id' => 'ADDRESS_ID']) }}'.replace('ADDRESS_ID', id), {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.status) {
          location.reload();
        } else {
          alert(data.message || 'Failed to set default address.');
        }
      })
      .catch(() => alert('Failed to set default address.'));
    });
  });

  // Make addresses available in JS for edit
  window.addresses = @json($addresses ?? []);
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
