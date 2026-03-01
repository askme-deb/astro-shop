@extends('layouts.app')

@section('title', 'Login with OTP')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="mb-4 text-center">Login with OTP</h3>
                    <form id="otp-login-form">
                        <div class="mb-3">
                            <label for="mobile_no" class="form-label">Mobile Number</label>
                            <input type="tel" class="form-control" id="mobile_no" name="mobile_no" placeholder="Enter mobile number" required>
                        </div>
                        <div class="mb-3">
                            <label for="country_code" class="form-label">Country Code</label>
                            <input type="text" class="form-control" id="country_code" name="country_code" value="+91" required>
                        </div>
                        <button type="button" class="btn btn-dark w-100" id="send-otp-btn">Send OTP</button>
                    </form>
                    <div id="otp-section" style="display:none;">
                        <div class="mb-3 mt-4">
                            <label for="otp" class="form-label">Enter OTP</label>
                            <input type="text" class="form-control" id="otp" name="otp" maxlength="4" placeholder="Enter OTP">
                        </div>
                        <button type="button" class="btn btn-success w-100" id="verify-otp-btn">Verify & Login</button>
                        <button type="button" class="btn btn-link w-100 mt-2" id="resend-otp-btn">Resend OTP</button>
                    </div>
                    <div id="otp-alert" class="alert d-none mt-3" role="alert"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // Basic JS for OTP login flow
    const sendOtpBtn = document.getElementById('send-otp-btn');
    const verifyOtpBtn = document.getElementById('verify-otp-btn');
    const resendOtpBtn = document.getElementById('resend-otp-btn');
    const otpSection = document.getElementById('otp-section');
    const otpAlert = document.getElementById('otp-alert');

    function showAlert(msg, type = 'info') {
        otpAlert.textContent = msg;
        otpAlert.className = 'alert alert-' + type;
        otpAlert.classList.remove('d-none');
    }

    function hideAlert() {
        otpAlert.classList.add('d-none');
    }
    if (sendOtpBtn) {
        sendOtpBtn.onclick = function() {
            hideAlert();
            const mobile = document.getElementById('mobile_no').value.trim();
            const country = document.getElementById('country_code').value.trim();
            fetch('/login/otp/request', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        mobile_no: mobile,
                        country_code: country,
                        context: 'login'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        otpSection.style.display = '';
                        showAlert(data.message, 'success');
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .catch(() => showAlert('Failed to send OTP', 'danger'));
        };
    }
    if (verifyOtpBtn) {
        verifyOtpBtn.onclick = function() {
            hideAlert();
            const mobile = document.getElementById('mobile_no').value.trim();
            const country = document.getElementById('country_code').value.trim();
            const otp = document.getElementById('otp').value.trim();
            fetch('/login/otp/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        mobile_no: mobile,
                        country_code: country,
                        otp: otp,
                        context: 'login'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect_url || '/dashboard';
                        }, 800);
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .catch(() => showAlert('Failed to verify OTP', 'danger'));
        };
    }
    if (resendOtpBtn) {
        resendOtpBtn.onclick = function() {
            hideAlert();
            const mobile = document.getElementById('mobile_no').value.trim();
            const country = document.getElementById('country_code').value.trim();
            fetch('/login/otp/resend', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        mobile_no: mobile,
                        country_code: country,
                        context: 'login'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .catch(() => showAlert('Failed to resend OTP', 'danger'));
        };
    }
</script>
@endpush