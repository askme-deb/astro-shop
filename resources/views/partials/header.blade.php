<!-- Top Offer Banner (Image) -->
<!-- Top Bar -->
<div class="top-bar">
    Free Shipping | 925 Silver Jewellery
</div>

<!-- Main Header -->
<div class="container">
    <div class="row align-items-center">
        <!-- Logo -->
        <div class="col-6 col-md-1 logo_warp">
            <a href="#"> <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo"></a>
        </div>

        <!-- Search (Full width on mobile) -->
        <div class="col-12 col-md-7 order-3 order-md-2 mt-3 mt-md-0">
            <div class="d-flex gap-2">
                <!-- Header Trigger -->
                <div class="header_pincode_box_subhead" id="openPincodePopup">
                    <span id="update-del-text">Update Delivery Pincode</span>

                    <svg aria-hidden="true" focusable="false" class="icon icon-caret" viewBox="0 0 10 6">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M9.354.646a.5.5 0 00-.708 0L5 4.293 1.354.646a.5.5 0 00-.708.708l4 4a.5.5 0 00.708 0l4-4a.5.5 0 000-.708z"
                            fill="currentColor">
                        </path>
                    </svg>
                </div>

                <!-- Popup Modal -->
                <div class="pincode-modal" id="pincodeModal">
                    <div class="pincode-modal-content">
                        <button class="pincode-close">&times;</button>

                        <h3>Enter Delivery Pincode</h3>
                        <p>Check product availability and delivery options</p>

                        <input type="text" id="pincodeInput" placeholder="Enter Pincode" maxlength="6">

                        <button class="pincode-submit">Check</button>
                    </div>
                </div>

                <div class="search-box flex-grow-1">
                    <input type="text" id="searchInput" class="form-control" placeholder='Search "Rings"'
                        autocomplete="off">

                    <div id="searchSuggestions" class="search-suggestions d-none"></div>
                </div>
            </div>
        </div>

        <!-- Desktop Icons -->
        <div class="col-6 col-md-4 text-end d-none d-md-block order-md-3">
            <div class="d-inline-flex gap-4 text-center">
                <!-- Cart Icon -->
                <div class="icon_warp position-relative" id="cartWrapper">
                    <a href="javascript:void(0)" onclick="toggleCart(event)">
                        <i class="fa fa-bag-shopping fs-5"></i>
                        <span id="cartCount"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <span id="cartCountValue">0</span>
                        </span>
                        <div class="icon-text">CART</div>
                    </a>

                    <!-- Mini Cart -->
                    <div class="mini-cart shadow" id="miniCart" style="display: none; position: absolute; right: 0; top: 100%; width: 330px; background: #fff; z-index: 9999; padding: 15px; border-radius: 8px;">
                        <h6 class="fw-bold mb-3">Shopping Cart</h6>
                        <div id="miniCartItems" style="max-height: 240px; overflow-y: auto; overflow-x: hidden; padding-right: 5px;">
                            <!-- Skeleton loader initially -->
                            <div class="mini-cart-skeleton">
                                <div class="skeleton-item d-flex gap-2 mb-3">
                                    <div class="skeleton-img"></div>
                                    <div class="flex-grow-1">
                                        <div class="skeleton-line w-75"></div>
                                        <div class="skeleton-line w-50 mt-2"></div>
                                    </div>
                                </div>
                                <div class="skeleton-item d-flex gap-2 mb-3">
                                    <div class="skeleton-img"></div>
                                    <div class="flex-grow-1">
                                        <div class="skeleton-line w-75"></div>
                                        <div class="skeleton-line w-50 mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fw-semibold">
                            <span>Total</span>
                            <span id="miniCartTotal">₹0.00</span>
                        </div>

                        <a href="/cart" class="btn btn-dark w-100 mt-3">View Cart</a>
                        <a href="{{ route('checkout.index') }}" class="btn btn-outline-dark w-100 mt-2">Checkout</a>
                    </div>
                </div>

                <style>
                    .skeleton-img {
                        width: 50px;
                        height: 50px;
                        background: #eee;
                        border-radius: 4px;
                        animation: skeleton-loading 1.5s infinite linear;
                    }
                    .skeleton-line {
                        height: 12px;
                        background: #eee;
                        border-radius: 2px;
                        animation: skeleton-loading 1.5s infinite linear;
                    }
                    .skeleton-line.w-75 { width: 75%; }
                    .skeleton-line.w-50 { width: 50%; }
                    @keyframes skeleton-loading {
                        0% { background-color: #f0f0f0; }
                        50% { background-color: #e0e0e0; }
                        100% { background-color: #f0f0f0; }
                    }
                    /* Custom Scrollbar for Mini Cart */
                    #miniCartItems::-webkit-scrollbar {
                        width: 4px;
                    }
                    #miniCartItems::-webkit-scrollbar-track {
                        background: #f1f1f1;
                    }
                    #miniCartItems::-webkit-scrollbar-thumb {
                        background: #888;
                        border-radius: 10px;
                    }
                    #miniCartItems::-webkit-scrollbar-thumb:hover {
                        background: #555;
                    }
                </style>

                <script>
                    let miniCartItemIdToRemove = null;

                    document.addEventListener('DOMContentLoaded', function () {
                        updateCartCount();
                    });

                    window.toggleCart = function(e) {
                        if(e) e.preventDefault();
                        if(e) e.stopPropagation();
                        console.log('Toggling cart...');
                        const miniCart = document.getElementById('miniCart');
                        if (miniCart.style.display === 'none' || miniCart.style.display === '') {
                            miniCart.style.display = 'block';
                            fetchMiniCart();
                        } else {
                            miniCart.style.display = 'none';
                        }
                    }

                    document.addEventListener('click', function(event) {
                        const cartWrapper = document.getElementById('cartWrapper');
                        const miniCart = document.getElementById('miniCart');
                        if (miniCart && miniCart.style.display === 'block' && cartWrapper && !cartWrapper.contains(event.target)) {
                            miniCart.style.display = 'none';
                        }
                    });

                    function updateCartCount() {
                        fetch('/api/cart/count', { credentials: 'include' })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status && typeof data.count !== 'undefined') {
                                    document.getElementById('cartCountValue').textContent = data.count;
                                }
                            })
                            .catch(() => {
                                document.getElementById('cartCountValue').textContent = 0;
                            });
                    }

                    function getSkeletonHtml() {
                        return `
                            <div class="mini-cart-skeleton">
                                <div class="skeleton-item d-flex gap-2 mb-3">
                                    <div class="skeleton-img"></div>
                                    <div class="flex-grow-1">
                                        <div class="skeleton-line w-75"></div>
                                        <div class="skeleton-line w-50 mt-2"></div>
                                    </div>
                                </div>
                                <div class="skeleton-item d-flex gap-2 mb-3">
                                    <div class="skeleton-img"></div>
                                    <div class="flex-grow-1">
                                        <div class="skeleton-line w-75"></div>
                                        <div class="skeleton-line w-50 mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    function fetchMiniCart() {
                        const miniCartItems = document.getElementById('miniCartItems');
                        miniCartItems.innerHTML = getSkeletonHtml();

                        fetch('/api/cart', { credentials: 'include' })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success' && data.data && data.data.length > 0) {
                                    renderMiniCartItems(data.data);
                                } else {
                                    miniCartItems.innerHTML = '<p class="text-center text-muted">Your cart is empty.</p>';
                                    document.getElementById('miniCartTotal').textContent = '₹0.00';
                                }
                            })
                            .catch(err => {
                                console.error('Error fetching cart:', err);
                                miniCartItems.innerHTML = '<p class="text-center text-danger">Failed to load cart.</p>';
                            });
                    }

                    function renderMiniCartItems(items) {
                        const container = document.getElementById('miniCartItems');
                        let html = '';
                        let total = 0;

                        items.forEach(item => {
                            const product = item.product || {};
                            const price = parseFloat(product.total_price || item.amount || 0);
                            const qty = parseInt(item.quantity || 1);
                            total += price * qty;
                            const imageUrl = product.image_url ? product.image_url : '/assets/images/product-1.jpg'; // Fallback

                            html += `
                                <div class="cart-item d-flex gap-2 mb-3">
                                    <img src="${imageUrl}" class="rounded" alt="${product.name || 'Product'}" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="flex-grow-1 text-start">
                                        <p class="mb-0 fw-semibold text-truncate" style="max-width: 150px;">${product.name || 'Unknown Product'}</p>
                                        <small>Qty: ${qty}</small>
                                        <p class="mb-0 text-muted">₹${price.toFixed(2)}</p>
                                    </div>
                                    <button class="btn btn-sm text-danger" onclick="removeMiniCartItem('${item.id}', this)">×</button>
                                </div>
                            `;
                        });

                        container.innerHTML = html;
                        document.getElementById('miniCartTotal').textContent = '₹' + total.toFixed(2);
                    }

                    function removeMiniCartItem(cartId, btn) {
                        miniCartItemIdToRemove = cartId;
                        const popup = document.getElementById('mini-cart-popup');
                        if (popup) popup.style.display = 'block';
                    }

                    function closeMiniCartPopup() {
                        miniCartItemIdToRemove = null;
                        const popup = document.getElementById('mini-cart-popup');
                        if (popup) popup.style.display = 'none';
                    }

                    function confirmMiniCartRemoveItem() {
                        if (!miniCartItemIdToRemove) return;

                        const cartId = miniCartItemIdToRemove;
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                        fetch('/api/cart/delete-item', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            credentials: 'include',
                            body: JSON.stringify({ cart_id: cartId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success || data.status) {
                                fetchMiniCart();
                                updateCartCount();
                                if (window.location.pathname === '/cart') {
                                    window.location.reload();
                                }
                            } else {
                                alert('Failed to remove item');
                            }
                        })
                        .catch(err => console.error(err))
                        .finally(() => {
                            closeMiniCartPopup();
                        });
                    }
                </script>

                <!-- Mini Cart Remove Confirmation Popup (separate ID to avoid conflict with cart page popup) -->
                <div class="popup" id="mini-cart-popup" style="display:none;">
                    <div class="popup-content">
                        <div class="popup-header">
                            <h3>Move from cart?</h3>
                            <span onclick="closeMiniCartPopup()" style="cursor:pointer">✕</span>
                        </div>

                        <p>Move this item to your wishlist and buy later.</p>

                        <div class="popup-actions">
                            <a onclick="confirmMiniCartRemoveItem()">Remove</a>
                            <a onclick="closeMiniCartPopup()">Cancel</a>
                        </div>
                    </div>
                </div>

                <div class="icon_warp">
                    @if(session()->has('auth.api_token'))
                        <form id="header-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                        <a href="javascript:void(0)" id="header-logout-trigger">
                            <i class="fa fa-user fs-5"></i>
                            <div class="icon-text">LOGOUT</div>
                        </a>
                    @else
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#authModal">
                            <i class="fa fa-user fs-5"></i>
                            <div class="icon-text">LOGIN</div>
                        </a>
                    @endif
                </div>


                <div class="icon_warp">
                    <a href="#"><i class="fa fa-heart fs-5"></i>
                        <div class="icon-text">WISHLIST</div>
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Mobile Bottom Navigation -->
<div class="mobile-footer d-md-none">
    <a href="#"><i class="fa fa-house"></i></a>
    <a href="#"><i class="fa fa-heart"></i></a>
    <a href="#" class="cart-icon position-relative">
        <i class="fa fa-bag-shopping"></i>
        <span class="badge bg-danger">0</span>
    </a>
    <a href="#"><i class="fa fa-user"></i></a>
</div>

<!-- Navigation -->
<div class="border-top border-bottom py-2">

    <div class="container">

        <!-- Mobile Toggle Button -->
        <div class="d-flex justify-content-between align-items-center d-md-none">
            <strong>Menu</strong>
            <button class="btn btn-outline-dark btn-sm" data-bs-toggle="collapse" data-bs-target="#mobileNav">
                <i class="fa fa-bars"></i>
            </button>
        </div>

        <!-- Nav Links -->
        <div class="collapse d-md-block mt-3 mt-md-0" id="mobileNav">
            <div class="nav-links text-center text-md-start">
                <!-- <a href="#">Shop by Category</a>
        <a href="#">Valentine's Sale is Live</a>
        <a href="#">Gifts for Him</a>
        <a href="#">Gifts for Her</a>
        <a href="#">GIVA Gift Card</a>
        <a href="#">Gift Store</a>
        <a href="#">Exclusive Collections</a>
        <a href="#">More at GIVA</a> -->
                <a href="#">Home</a>
                <a href="#">Shop</a>
                <a href="#">Rudraksha</a>
                <a href="#">Gemstones</a>
                <a href="#">About</a>
                <a href="#">Contact</a>

            </div>
        </div>

    </div>

</div>

<!-- Auth Modal -->
<div class="modal fade" id="authModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">

            <!-- Close -->
            <button type="button" class="btn-close position-absolute end-0 m-3" data-bs-dismiss="modal"></button>

            <h5 class="mb-3">Login with OTP</h5>

            <div id="header-otp-alert" class="alert alert-info d-none" role="alert"></div>

            <!-- STEP 1: Mobile input -->
            <div id="header-otp-step-mobile">
                <div class="mb-3">
                    <label class="form-label">Mobile Number</label>
                    <input type="tel" id="header-otp-mobile" class="form-control" placeholder="Enter mobile number" autocomplete="tel" inputmode="numeric">
                </div>
                <button type="button" class="btn btn-dark w-100 cdtr" id="header-otp-send-btn">Send OTP</button>
            </div>

            <!-- STEP 2: OTP verify -->
            <div id="header-otp-step-verify" style="display:none;">
                <div class="mb-2 small text-muted" id="header-otp-instructions">
                    Enter the OTP sent to your mobile number.
                </div>
                <div class="mb-3">
                    <label class="form-label">Mobile Number</label>
                    <input type="tel" id="header-otp-mobile-readonly" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">OTP</label>
                    <div class="d-flex gap-2 justify-content-between" style="max-width: 220px;">
                        <input type="tel" maxlength="1" class="form-control text-center header-otp-digit" inputmode="numeric" autocomplete="one-time-code">
                        <input type="tel" maxlength="1" class="form-control text-center header-otp-digit" inputmode="numeric" autocomplete="one-time-code">
                        <input type="tel" maxlength="1" class="form-control text-center header-otp-digit" inputmode="numeric" autocomplete="one-time-code">
                        <input type="tel" maxlength="1" class="form-control text-center header-otp-digit" inputmode="numeric" autocomplete="one-time-code">
                    </div>
                </div>
                <button type="button" class="btn btn-success w-100 mb-2" id="header-otp-verify-btn">Verify &amp; Login</button>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-link p-0" id="header-otp-change-mobile">Change mobile</button>
                    <button type="button" class="btn btn-link p-0" id="header-otp-resend-btn">Resend OTP</button>
                    <span class="small text-muted" id="header-otp-resend-timer" style="display:none;"></span>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const logoutTrigger = document.getElementById('header-logout-trigger');
        if (logoutTrigger) {
            logoutTrigger.addEventListener('click', function () {
                const form = document.getElementById('header-logout-form');
                if (!form) return;

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                })
                    .then(() => window.location.reload());
            });
        }

        const alertBox = document.getElementById('header-otp-alert');
        const stepMobile = document.getElementById('header-otp-step-mobile');
        const stepVerify = document.getElementById('header-otp-step-verify');
        const mobileInput = document.getElementById('header-otp-mobile');
        const mobileReadonly = document.getElementById('header-otp-mobile-readonly');
        const otpInputs = Array.from(document.querySelectorAll('.header-otp-digit'));
        const sendBtn = document.getElementById('header-otp-send-btn');
        const verifyBtn = document.getElementById('header-otp-verify-btn');
        const changeMobileBtn = document.getElementById('header-otp-change-mobile');
        const resendBtn = document.getElementById('header-otp-resend-btn');
        const resendTimer = document.getElementById('header-otp-resend-timer');

        let headerResendCountdown = null;

        function showHeaderAlert(message, type = 'info') {
            if (!alertBox) return;
            alertBox.classList.remove('d-none', 'alert-info', 'alert-danger', 'alert-success');
            alertBox.classList.add('alert-' + type);
            alertBox.textContent = message;
        }

        function clearHeaderAlert() {
            if (!alertBox) return;
            alertBox.classList.add('d-none');
            alertBox.textContent = '';
        }

        function setHeaderLoading(button, isLoading) {
            if (!button) return;
            button.disabled = isLoading;
            if (isLoading) {
                button.dataset.originalText = button.innerText;
                button.innerText = 'Please wait...';
            } else if (button.dataset.originalText) {
                button.innerText = button.dataset.originalText;
            }
        }

        function startHeaderResendCountdown(seconds) {
            if (!resendTimer || !resendBtn) return;
            let remaining = seconds;
            resendTimer.style.display = 'inline';
            resendBtn.style.pointerEvents = 'none';
            resendBtn.style.opacity = '0.5';
            resendTimer.textContent = '(' + remaining + 's)';

            if (headerResendCountdown) clearInterval(headerResendCountdown);
            headerResendCountdown = setInterval(function () {
                remaining -= 1;
                if (remaining <= 0) {
                    clearInterval(headerResendCountdown);
                    resendTimer.style.display = 'none';
                    resendBtn.style.pointerEvents = 'auto';
                    resendBtn.style.opacity = '1';
                } else {
                    resendTimer.textContent = '(' + remaining + 's)';
                }
            }, 1000);
        }

        function headerPostJson(url, payload, onSuccess) {
            clearHeaderAlert();
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                credentials: 'include',
                body: JSON.stringify(payload),
            })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({ success: false, message: 'Unexpected server response.' }));

                    if (!response.ok || data.success === false) {
                        const message = data.message || 'Unable to process request.';
                        showHeaderAlert(message, 'danger');
                        return;
                    }

                    onSuccess(data);
                })
                .catch(() => {
                    showHeaderAlert('Unable to reach authentication service. Please try again.', 'danger');
                });
        }

        function getHeaderOtp() {
            if (!otpInputs.length) return '';
            return otpInputs.map(function (input) {
                return (input.value || '').trim();
            }).join('');
        }

        function clearHeaderOtp() {
            otpInputs.forEach(function (input) {
                input.value = '';
            });
            if (otpInputs[0]) {
                otpInputs[0].focus();
            }
        }

        // OTP input UX: auto-advance and backspace behavior
        otpInputs.forEach(function (input, index) {
            input.addEventListener('input', function (e) {
                const value = input.value.replace(/[^0-9]/g, '');
                input.value = value.slice(-1);

                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        if (sendBtn) {
            sendBtn.addEventListener('click', function () {
                const mobile = (mobileInput?.value || '').trim();
                if (!mobile) {
                    showHeaderAlert('Please enter your mobile number.', 'danger');
                    return;
                }

                setHeaderLoading(sendBtn, true);

                headerPostJson("{{ route('login.otp.request') }}", {
                    mobile_no: mobile,
                    country_code: '91',
                    context: 'header',
                }, function (data) {
                    showHeaderAlert(data.message || 'OTP sent successfully.', 'success');
                    if (mobileReadonly) mobileReadonly.value = mobile;
                    if (stepMobile && stepVerify) {
                        stepMobile.style.display = 'none';
                        stepVerify.style.display = 'block';
                    }
                    startHeaderResendCountdown(30);
                });

                setTimeout(function () {
                    setHeaderLoading(sendBtn, false);
                }, 600);
            });
        }

        if (verifyBtn) {
            verifyBtn.addEventListener('click', function () {
                const mobile = (mobileReadonly?.value || '').trim();
                const otp = getHeaderOtp();

                if (!otp || otp.length < 4) {
                    showHeaderAlert('Please enter the 4-digit OTP.', 'danger');
                    return;
                }

                setHeaderLoading(verifyBtn, true);

                headerPostJson("{{ route('login.otp.verify') }}", {
                    mobile_no: mobile,
                    country_code: '+91',
                    otp: otp,
                    context: 'header',
                }, function (data) {
                    showHeaderAlert(data.message || 'Logged in successfully.', 'success');
                    const modal = document.getElementById('authModal');
                    if (modal) {
                        const bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                        bsModal.hide();
                    }
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.reload();
                    }
                });

                setTimeout(function () {
                    setHeaderLoading(verifyBtn, false);
                }, 600);
            });
        }

        if (changeMobileBtn) {
            changeMobileBtn.addEventListener('click', function () {
                if (stepMobile && stepVerify) {
                    stepVerify.style.display = 'none';
                    stepMobile.style.display = 'block';
                    clearHeaderAlert();
                    clearHeaderOtp();
                }
            });
        }

        if (resendBtn) {
            resendBtn.addEventListener('click', function () {
                const mobile = (mobileReadonly?.value || '').trim();
                if (!mobile) {
                    showHeaderAlert('Mobile number is missing. Please go back and enter it again.', 'danger');
                    return;
                }

                setHeaderLoading(resendBtn, true);

                headerPostJson("{{ route('login.otp.resend') }}", {
                    mobile_no: mobile,
                    country_code: '+91',
                    context: 'header',
                }, function (data) {
                    showHeaderAlert(data.message || 'OTP resent.', 'success');
                    startHeaderResendCountdown(30);
                });

                setTimeout(function () {
                    setHeaderLoading(resendBtn, false);
                }, 600);
            });
        }
    });
</script>
