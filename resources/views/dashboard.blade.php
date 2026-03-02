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
                                <div id="profilePlaceholder" class="rounded-circle border bg-secondary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 80px; height: 80px; font-size: 2rem;">
                                    {{ strtoupper(substr(session('auth.user.first_name'), 0, 1)) }}{{ strtoupper(substr(session('auth.user.last_name'), 0, 1)) }}
                                </div>
                                <label for="avatarUpload"
                                    class="position-absolute bottom-0 end-0 bg-theme rounded-circle p-1"
                                    style="cursor:pointer;">
                                    {{-- <i class="bi bi-pencil-square text-white"></i> --}}
                                </label>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">{{ session('auth.user.first_name') }}
                                    {{ session('auth.user.last_name') }}</h5>
                                <span class="text-muted">{{ session('auth.user.mobile_no') }}</span>
                            </div>
                            <a href="javascript:;" class="btn btn-outline-dark btn-sm ms-auto px-4" id="editProfileBtn">Edit Profile</a>
                        </div>

                        <form class="profile-form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="fw-semibold mb-1">First Name</label>
                                    <input type="text" class="form-control" value="{{ session('auth.user.first_name') }}" readonly id="firstName">
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold mb-1">Last Name</label>
                                    <input type="text" class="form-control" value="{{ session('auth.user.last_name') }}" readonly id="lastName">
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold mb-1">Email</label>
                                    <input type="email" class="form-control" value="{{ session('auth.user.email') }}" readonly id="email">
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold mb-1">Phone</label>
                                    <input type="text" class="form-control" value="{{ session('auth.user.mobile_no') }}" readonly id="phone">
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold mb-1">Gender</label>
                                    <select class="form-select" disabled id="gender">
                                        <option value="Male" {{ session('auth.user.gender') == 'Male' ? 'selected' : '' }}>
                                            Male</option>
                                        <option value="Female" {{ session('auth.user.gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ session('auth.user.gender') == 'Other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
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

                    .profile-form input,
                    .profile-form select {
                        font-size: 1rem;
                    }

                    .profile-form .form-control:focus,
                    .profile-form .form-select:focus {
                        border-color: #0d6efd;
                        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, .15);
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
        document.addEventListener('DOMContentLoaded', function () {
            var editBtn = document.getElementById('editProfileBtn');
            if (editBtn) {
                editBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var firstName = document.getElementById('firstName');
                    var lastName = document.getElementById('lastName');
                    var email = document.getElementById('email');
                    var phone = document.getElementById('phone');
                    var gender = document.getElementById('gender');
                    if (firstName) firstName.readOnly = false;
                    if (lastName) lastName.readOnly = false;
                    if (email) email.readOnly = false;
                    if (phone) phone.readOnly = false;
                    if (gender) gender.disabled = false;
                });
            }
        });
    document.getElementById('avatarUpload').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (ev) {
                document.getElementById('profileAvatar').src = ev.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
