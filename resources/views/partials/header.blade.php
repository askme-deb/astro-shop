<!-- Top Offer Banner (Image) -->
<!-- Top Bar -->
<div class="top-bar">
    Free Shipping | 100% Certified Products | 30 Days Return Policy
</div>

<!-- Main Header -->

  <div class="container">
    <div class="row align-items-center">
      <!-- Logo -->
      <div class="col-6 col-md-3 logo_warp">
        <a href="https://astrorajumaharaj.com"> <img src="{{ asset('assets/images/Logo.png') }}"></a>
      </div>

      <!-- Search (Full width on mobile) -->
      <div class="col-12 col-md-5 order-3 order-md-2 mt-3 mt-md-0">
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
            <input type="text"
              id="searchInput"
              class="form-control"
              placeholder='Search "Rings"'
              autocomplete="off">

            <div id="searchSuggestions" class="search-suggestions "></div>
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
              <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
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

                    .skeleton-line.w-75 {
                        width: 75%;
                    }

                    .skeleton-line.w-50 {
                        width: 50%;
                    }

                    @keyframes skeleton-loading {
                        0% {
                            background-color: #f0f0f0;
                        }

                        50% {
                            background-color: #e0e0e0;
                        }

                        100% {
                            background-color: #f0f0f0;
                        }
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

                    document.addEventListener('DOMContentLoaded', function() {
                        updateCartCount();
                        if (typeof updateWishlistCount === 'function') {
                            updateWishlistCount();
                        }
                    });

                    window.toggleCart = function(e) {
                        if (e) e.preventDefault();
                        if (e) e.stopPropagation();
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
                        fetch('/api/cart/count', {
                                credentials: 'include'
                            })
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

                    function updateWishlistCount() {
                        fetch('/api/wishlist/count', {
                                credentials: 'include'
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status && typeof data.count !== 'undefined') {
                                    const el = document.getElementById('wishlistCountValue');
                                    if (el) {
                                        el.textContent = data.count;
                                    }
                                }
                            })
                            .catch(() => {
                                const el = document.getElementById('wishlistCountValue');
                                if (el) {
                                    el.textContent = 0;
                                }
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

                        fetch('/api/cart', {
                                credentials: 'include'
                            })
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

                        // Determine if user is logged in
                        let payload = { cart_id: cartId };
                        let isLoggedIn = !!document.querySelector('form#header-logout-form');
                        if (isLoggedIn) {
                            // Logged-in user: do not send guest_user_id or user_id
                            // Token is sent via cookie automatically
                        } else {
                            // Guest: send guest_user_id, do not send user_id
                            let guestUserId = localStorage.getItem('guest_user_id') || '';
                            if (!guestUserId) {
                                // Try to get from cookie
                                const match = document.cookie.match(/guest_user_id=([^;]+)/);
                                if (match) guestUserId = match[1];
                            }
                            if (guestUserId) payload.guest_user_id = guestUserId;
                        }
                        fetch('/api/cart/delete-item', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            credentials: 'include',
                            body: JSON.stringify(payload)
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
          </div>







             <div class="icon_warp">
                        @if(session()->has('auth.api_token'))
                        <form id="header-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                        <a href="{{ route('dashboard') }}">
                            <i class="fa fa-user fs-5"></i>
                            <div class="icon-text">DASHBOARD</div>
                        </a>
                        {{-- <a href="javascript:void(0)" id="header-logout-trigger">
                            <i class="fa fa-sign-out-alt fs-5"></i>
                            <div class="icon-text">LOGOUT</div>
                        </a> --}}
                    @else
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#authModal">
                            <i class="fa fa-user fs-5"></i>
                            <div class="icon-text">LOGIN</div>
                        </a>
                    @endif
                </div>


                    <div class="icon_warp position-relative" id="wishlistWrapper">
                        <a href="#">
                            <i class="fa fa-heart fs-5"></i>
                            <span id="wishlistCountValue" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                0
                            </span>
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
  <div class="border-bottom py-2">

    <div class="container">

      <!-- Mobile Toggle Button -->
      <div class="d-flex justify-content-between align-items-center d-md-none">
        <strong>Menu</strong>
        <button class="btn btn-outline-dark btn-sm"
          data-bs-toggle="collapse"
          data-bs-target="#mobileNav">
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
                    @php
                        $menuItems = [
                            ['label' => 'Home', 'url' => url('/')],
                            ['label' => 'Products', 'url' => url('/products')],
                            ['label' => 'About', 'url' => url('https://astrorajumaharaj.com/about')],
                            ['label' => 'Contact', 'url' => url('https://astrorajumaharaj.com/contact')],
                        ];
                    @endphp
                    @foreach($menuItems as $item)
                        <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    @endforeach
     @php
         $categoryMenu = app(\App\Services\Api\CategoryApiService::class)->getCategories(['is_menu' => true]);
     @endphp
     @foreach($categoryMenu as $cat)
         <a href="/{{ $cat['slug'] ?? $cat['id'] }}">{{ $cat['name'] ?? 'Category' }}</a>
     @endforeach
        </div>
      </div>

    </div>

  </div>

@push('styles')
<style>
    .auth-modal .modal-dialog {
        max-width: 760px;
    }

    .auth-modal .modal-content {
        border: 0;
        border-radius: 1.15rem;
        overflow: hidden;
        box-shadow: 0 20px 46px rgba(15, 23, 42, 0.16);
        background: #ffffff;
    }

    .auth-side-panel {
        min-height: 100%;
        background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%);
    }

    .auth-logo-shell {
        width: 112px;
        height: 112px;
        margin: 0 auto 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.9rem;
        background: rgba(255, 248, 236, 0.88);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4);
    }

    .auth-logo-shell img {
        max-width: 78px;
        display: block;
    }

    .auth-side-panel h3 {
        margin-bottom: 0.45rem;
        font-size: 1.7rem;
        font-weight: 700;
        color: #fff;
    }

    .auth-side-panel p {
        max-width: 220px;
        margin: 0 auto;
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.9rem;
        line-height: 1.45;
    }

    .auth-stars {
        color: #fff;
        opacity: 0.95;
    }

    .auth-panel-right {
        padding: 1.25rem 1.1rem !important;
        background: #ffffff;
        color: #ff9800;
    }

    .auth-tabs {
        gap: 0.45rem;
    }

    .auth-tabs .nav-item {
        flex: 1 1 0;
    }

    .auth-tab {
        width: 100%;
        min-height: 2.4rem;
        border: 1px solid #ff9800;
        border-radius: 0.35rem;
        background: #ffffff !important;
        color: #000000 !important;
        justify-content: center;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.18s ease;
    }

    .auth-modal .nav-pills .nav-link.auth-tab,
    #login-tab,
    #register-tab {
        color: #000000 !important;
        background-color: #ffffff !important;
        border-color: #ff9800 !important;
    }

    .auth-modal .nav-pills .nav-link.auth-tab.active,
    .auth-modal .nav-pills .show > .nav-link.auth-tab,
    #login-tab.active,
    #register-tab.active {
        color: #ffffff !important;
        background-color: #ff9800 !important;
        border-color: #ff9800 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
    }
    .auth-modal .form-label {
        color: #ff9800;
        font-weight: 500;
        margin-bottom: 0.35rem;
        font-size: 0.92rem;
    }

    .auth-modal h4 {
        font-size: 1.6rem;
    }

    .auth-modal h4,
    .auth-modal .text-muted,
    .auth-modal .btn-link,
    .auth-modal .invalid-feedback {
        color: #ff9800 !important;
    }

    .auth-modal .form-control,
    .auth-modal .input-group-text,
    .auth-modal .btn,
    .auth-modal .btn-outline-secondary {
        border-radius: 0.45rem;
    }

    .auth-modal .form-control,
    .auth-modal .input-group-text {
        min-height: 40px;
        border-color: #bdbdbd;
        box-shadow: none;
        font-size: 0.95rem;
    }

    .auth-modal .form-control:focus,
    .auth-modal .input-group-text:focus-within {
        border-color: #ff9800;
        box-shadow: 0 0 0 0.18rem rgba(255, 152, 0, 0.22);
    }

    .auth-modal .btn-primary,
    .auth-modal .btn-warning {
        border: 1px solid #ff9800;
        background: #ff9800;
        color: #ffffff;
        min-height: 42px;
        font-size: 0.95rem;
        padding-top: 0.45rem;
        padding-bottom: 0.45rem;
    }

    .auth-modal .btn-primary:hover,
    .auth-modal .btn-warning:hover {
        background: #ff9800;
        border-color: #ff9800;
        color: #ffffff;
    }

    .auth-modal .btn-outline-secondary {
        border-color: #ff9800;
        color: #ff9800;
        background: #fff;
        min-width: 44px;
    }

    .auth-modal .otp-box {
        padding-top: 0.15rem;
    }

    .auth-modal .header-otp-digit {
        text-align: center;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .auth-modal .alert {
        border-radius: 0.45rem;
    }

    .auth-modal .btn-google,
    .auth-modal .btn-facebook {
        border: 1px solid #e5e7eb;
        background: #fff;
    }

    .auth-modal .text-link-orange {
        color: #ff9800 !important;
    }

    @media (max-width: 767px) {
        .auth-modal .modal-dialog {
            margin: 12px;
        }

        .auth-panel-right {
            padding: 1rem !important;
        }

        .auth-tabs {
            gap: 0.35rem;
        }

        .auth-tab {
            font-size: 0.88rem;
            padding-inline: 0.45rem;
        }
    }
</style>
@endpush

<!-- Modal -->
<div class="modal fade auth-modal" id="authModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="row g-0">
               <div class="col-md-5 d-none d-md-flex align-items-center justify-content-center text-white p-4" style="background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%);">
                    <div class="text-center w-100">
                        <div style="background:rgba(255,255,255,0.85);border-radius:1rem;display:inline-block;padding:0.5rem 1rem;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                            <img src="{{ asset('assets/images/Logo.png') }}" alt="Logo" class="mb-4 animate__animated animate__fadeInDown" style="max-width:100px;display:block;">
                        </div>
                        <h3 class="fw-bold mb-2 animate__animated animate__fadeInUp">Welcome!</h3>
                        <p class="mb-0 animate__animated animate__fadeInUp animate__delay-1s">Sign in or create an account to access personalized astrology services.</p>
                        <div class="mt-4 animate__animated animate__fadeIn animate__delay-2s">
                            <i class="bi bi-stars" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 col-12 p-4 bg-white auth-panel-right">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-semibold mb-0" id="authModalLabel">Account Access</h4>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <ul class="nav nav-pills nav-justified mb-4 auth-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link auth-tab active fw-semibold d-flex align-items-center gap-2" id="login-tab" type="button" role="tab" aria-selected="true" onclick="showTab('login')">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link auth-tab fw-semibold d-flex align-items-center gap-2" id="register-tab" type="button" role="tab" aria-selected="false" onclick="showTab('register')">
                                <i class="bi bi-person-plus"></i> Register
                            </button>
                        </li>
                    </ul>
                    <div id="loginBox">
                        <div id="loginFields">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="header-login-email" placeholder="Enter your email" autocomplete="username">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="header-login-password" placeholder="Enter your password" autocomplete="current-password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('header-login-password')" aria-label="Show or hide password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="text-end mt-1">
                                    <a href="#" class="small text-decoration-none text-link-orange" onclick="showForgotForm(event)" style="color: #ff9800;">Forgot Password?</a>
                                </div>
                            </div>
                            <div id="header-login-error" class="alert alert-danger mt-2 d-none"></div>
                        </div>
                        <form id="forgotForm" class="flex-column gap-2 mt-2" style="max-width: 100%; display:none;">
                            <input type="email" class="form-control mb-2" id="header-forgot-email" placeholder="Enter your email for reset" style="font-size:0.95rem;">
                            <button type="button" class="btn w-100" id="header-forgot-submit" style="border:1px solid #ff9800;color:#ff9800;background:#fff;">Send Reset Link</button>
                            <button type="button" class="btn btn-link w-100 text-link-orange" onclick="hideForgotForm(event)" style="color:#ff9800;">Back to Login</button>
                        </form>
                        <div id="loginButtons">
                            <button class="btn btn-primary w-100 mb-2" type="button" id="header-login-submit" style="border:1px solid #ff9800;color:#fff;background:#ff9800;">Login</button>
                            <div class="text-center my-2 text-muted">OR</div>
                            <button class="btn w-100 mb-2" type="button" onclick="showOtpLogin()" style="border:1px solid #ff9800;color:#ff9800;background:#fff;">Login with OTP</button>
                        </div>
                        <div class="otp-box mt-2" id="otpSection" style="display:none">
                            <div id="header-otp-alert" class="alert d-none mb-2" role="alert"></div>
                            <div id="header-otp-step-mobile">
                                <label class="form-label">Mobile Number</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text" style="border-color: #ff9800; color:#ff9800;">+91</span>
                                    <input type="tel" class="form-control" id="header-otp-mobile" style="border-color: #ff9800; color:#ff9800;" maxlength="15" placeholder="Enter your mobile number" autocomplete="tel">
                                </div>
                                <button class="btn btn-warning w-100 mb-2" id="header-otp-send-btn" type="button" style="background: #ff9800; border-color:#ff9800; color: #ffffff;">Send OTP</button>
                            </div>
                            <div id="header-otp-step-verify" style="display:none;">
                                <label class="form-label fw-semibold text-link-orange">Enter OTP</label>
                                <div class="d-flex align-items-center mb-3 gap-2">
                                    <input type="tel" class="form-control border border-warning" id="header-otp-mobile-readonly" readonly style="width:100%; font-weight:500; color:#ff9800; background:#fffbe6; border-color:#ff9800 !important;">
                                    <a href="#" id="header-otp-change-mobile" style="color:#ff9800; font-size:0.97rem; text-decoration:underline; cursor:pointer;">Change</a>
                                </div>
                                <div class="d-flex gap-2 justify-content-center mb-3">
                                    <input type="text" class="form-control text-center header-otp-digit border-2 border-warning" maxlength="1" style="width:2.5rem; font-size:1.5rem; background:#fffbe6; color:#ff9800; box-shadow:none; border-color:#ff9800 !important;" autocomplete="one-time-code">
                                    <input type="text" class="form-control text-center header-otp-digit border-2 border-warning" maxlength="1" style="width:2.5rem; font-size:1.5rem; background:#fffbe6; color:#ff9800; box-shadow:none; border-color:#ff9800 !important;" autocomplete="one-time-code">
                                    <input type="text" class="form-control text-center header-otp-digit border-2 border-warning" maxlength="1" style="width:2.5rem; font-size:1.5rem; background:#fffbe6; color:#ff9800; box-shadow:none; border-color:#ff9800 !important;" autocomplete="one-time-code">
                                    <input type="text" class="form-control text-center header-otp-digit border-2 border-warning" maxlength="1" style="width:2.5rem; font-size:1.5rem; background:#fffbe6; color:#ff9800; box-shadow:none; border-color:#ff9800 !important;" autocomplete="one-time-code">
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <button class="btn btn-warning flex-grow-1 me-2 fw-semibold px-4 py-2" id="header-otp-verify-btn" type="button" style="background: #ff9800; border-color:#ff9800; color: #ffffff;">Verify OTP</button>
                                    <button class="btn btn-link p-0 fw-semibold text-link-orange" id="header-otp-resend-btn" type="button">Resend</button>
                                    <span id="header-otp-resend-timer" style="display:none; margin-left:0.5rem; color:#888; font-size:0.95rem;"></span>
                                </div>
                            </div>
                            <button class="btn btn-link w-100 mt-2 text-link-orange" type="button" onclick="showNormalLogin()" style="color:#ff9800;">Back to Password Login</button>
                        </div>
                    </div>
                    <div id="registerBox" style="display:none">
                        <form id="registerForm">
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" id="regFirstName" name="first_name" placeholder="Enter your first name" required autocomplete="given-name">
                                <div class="invalid-feedback" id="regFirstNameError"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="regLastName" name="last_name" placeholder="Enter your last name" required autocomplete="family-name">
                                <div class="invalid-feedback" id="regLastNameError"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="regMobile" name="mobile_no" placeholder="Enter your mobile number" required autocomplete="tel">
                                <div class="invalid-feedback" id="regMobileError"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="regEmail" name="email" placeholder="Enter your email" required autocomplete="email">
                                <div class="invalid-feedback" id="regEmailError"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="regPassword" name="password" placeholder="Create a password" required autocomplete="new-password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('regPassword')" aria-label="Show or hide password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="regPasswordError"></div>
                            </div>
                            <div id="register-error" class="alert alert-danger mt-2 d-none"></div>
                            <div id="register-success" class="alert alert-success mt-2 d-none"></div>
                            <button type="submit" class="btn btn-primary w-100" style="border:1px solid #ff9800;color:#fff;background:#ff9800;">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // Clear auth modal state when modal is closed
        var authModalEl = document.getElementById('authModal');
        if (authModalEl) {
            authModalEl.addEventListener('hidden.bs.modal', function () {
                var otpInputs = document.querySelectorAll('.header-otp-digit');
                otpInputs.forEach(function(input) {
                    input.value = '';
                });
                if (typeof window.showTab === 'function') window.showTab('login');
                if (typeof window.showNormalLogin === 'function') window.showNormalLogin();
                var mobileStep = document.getElementById('header-otp-step-mobile');
                var verifyStep = document.getElementById('header-otp-step-verify');
                var otpAlert = document.getElementById('header-otp-alert');
                var mobileField = document.getElementById('header-otp-mobile');
                var loginError = document.getElementById('header-login-error');
                var registerError = document.getElementById('register-error');
                var registerSuccess = document.getElementById('register-success');

                if (mobileStep) mobileStep.style.display = 'block';
                if (verifyStep) verifyStep.style.display = 'none';
                if (otpAlert) otpAlert.classList.add('d-none');
                if (mobileField) mobileField.value = '';
                if (loginError) {
                    loginError.classList.add('d-none');
                    loginError.textContent = '';
                }
                if (registerError) {
                    registerError.classList.add('d-none');
                    registerError.textContent = '';
                }
                if (registerSuccess) {
                    registerSuccess.classList.add('d-none');
                    registerSuccess.textContent = '';
                }
            });
        }

    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Delivery Pincode popup logic
        const pincodeTrigger = document.getElementById('openPincodePopup');
        const pincodeModal = document.getElementById('pincodeModal');
        const pincodeInput = document.getElementById('pincodeInput');
        const pincodeCloseBtn = pincodeModal ? pincodeModal.querySelector('.pincode-close') : null;
        const pincodeSubmitBtn = pincodeModal ? pincodeModal.querySelector('.pincode-submit') : null;
        const updateDelText = document.getElementById('update-del-text');

        function openPincodeModal() {
            if (!pincodeModal) return;
            pincodeModal.style.display = 'flex';
        }

        function closePincodeModal() {
            if (!pincodeModal) return;
            pincodeModal.style.display = 'none';
        }

        // Load saved pincode from localStorage on page load
        try {
            const savedPincode = window.localStorage.getItem('delivery_pincode');
            if (savedPincode && updateDelText) {
                updateDelText.textContent = 'Deliver to ' + savedPincode;
            }
            if (savedPincode && pincodeInput) {
                pincodeInput.value = savedPincode;
            }
        } catch (e) {
            // localStorage may be unavailable; fail silently
        }

        if (pincodeTrigger && pincodeModal) {
            pincodeTrigger.addEventListener('click', function () {
                openPincodeModal();
            });
        }

        if (pincodeCloseBtn) {
            pincodeCloseBtn.addEventListener('click', function () {
                closePincodeModal();
            });
        }

        // Close when clicking outside the modal content
        if (pincodeModal) {
            pincodeModal.addEventListener('click', function (event) {
                if (event.target === pincodeModal) {
                    closePincodeModal();
                }
            });
        }

        if (pincodeSubmitBtn && pincodeInput) {
            pincodeSubmitBtn.addEventListener('click', function () {
                const raw = (pincodeInput.value || '').trim();

                // Basic validation: 6-digit numeric pincode
                if (!/^\d{6}$/.test(raw)) {
                    alert('Please enter a valid 6-digit pincode.');
                    return;
                }

                try {
                    window.localStorage.setItem('delivery_pincode', raw);
                } catch (e) {
                    // Ignore storage errors
                }

                if (updateDelText) {
                    updateDelText.textContent = 'Deliver to ' + raw;
                }

                closePincodeModal();
            });
        }

        const logoutTrigger = document.getElementById('header-logout-trigger');
        if (logoutTrigger) {
            logoutTrigger.addEventListener('click', function() {
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
        const loginBox = document.getElementById('loginBox');
        const registerBox = document.getElementById('registerBox');
        const loginTab = document.getElementById('login-tab');
        const registerTab = document.getElementById('register-tab');
        const loginFields = document.getElementById('loginFields');
        const loginButtons = document.getElementById('loginButtons');
        const otpSection = document.getElementById('otpSection');
        const forgotForm = document.getElementById('forgotForm');
        const loginError = document.getElementById('header-login-error');
        const loginSubmit = document.getElementById('header-login-submit');
        const registerForm = document.getElementById('registerForm');
        const registerError = document.getElementById('register-error');
        const registerSuccess = document.getElementById('register-success');
        const forgotSubmit = document.getElementById('header-forgot-submit');

        let headerResendCountdown = null;

        function resetRegisterMessages() {
            if (registerError) {
                registerError.classList.add('d-none');
                registerError.textContent = '';
            }
            if (registerSuccess) {
                registerSuccess.classList.add('d-none');
                registerSuccess.textContent = '';
            }
        }

        function showInlineMessage(element, message) {
            if (!element) return;
            element.textContent = message;
            element.classList.remove('d-none');
        }

        function clearRegisterFieldErrors() {
            [
                ['regFirstName', 'regFirstNameError'],
                ['regLastName', 'regLastNameError'],
                ['regMobile', 'regMobileError'],
                ['regEmail', 'regEmailError'],
                ['regPassword', 'regPasswordError'],
            ].forEach(function(field) {
                var input = document.getElementById(field[0]);
                var error = document.getElementById(field[1]);
                if (input) input.classList.remove('is-invalid');
                if (error) error.textContent = '';
            });
        }

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

        function setHeaderLoading(button, isLoading, loadingText) {
            if (!button) return;
            button.disabled = isLoading;
            if (isLoading) {
                button.dataset.originalText = button.innerText;
                button.innerText = loadingText || 'Please wait...';
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
            headerResendCountdown = setInterval(function() {
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
                    const data = await response.json().catch(() => ({
                        success: false,
                        message: 'Unexpected server response.'
                    }));

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
            return otpInputs.map(function(input) {
                return (input.value || '').trim();
            }).join('');
        }

        function clearHeaderOtp() {
            otpInputs.forEach(function(input) {
                input.value = '';
            });
            if (otpInputs[0]) {
                otpInputs[0].focus();
            }
        }

        window.togglePassword = function(fieldId) {
            const field = document.getElementById(fieldId);
            if (!field) return;
            field.type = field.type === 'password' ? 'text' : 'password';
        };

        window.showNormalLogin = function() {
            if (loginFields) loginFields.style.display = 'block';
            if (loginButtons) loginButtons.style.display = 'block';
            if (forgotForm) forgotForm.style.display = 'none';
            if (otpSection) otpSection.style.display = 'none';
            if (stepMobile) stepMobile.style.display = 'block';
            if (stepVerify) stepVerify.style.display = 'none';
            if (mobileInput) mobileInput.value = '';
            if (mobileReadonly) mobileReadonly.value = '';
            clearHeaderAlert();
            clearHeaderOtp();
            if (loginError) {
                loginError.classList.add('d-none');
                loginError.textContent = '';
            }
        };

        window.showOtpLogin = function() {
            if (loginFields) loginFields.style.display = 'none';
            if (loginButtons) loginButtons.style.display = 'none';
            if (forgotForm) forgotForm.style.display = 'none';
            if (otpSection) otpSection.style.display = 'block';
            if (stepMobile) stepMobile.style.display = 'block';
            if (stepVerify) stepVerify.style.display = 'none';
            if (mobileInput) mobileInput.value = '';
            if (mobileReadonly) mobileReadonly.value = '';
            clearHeaderAlert();
            clearHeaderOtp();
            if (loginError) {
                loginError.classList.add('d-none');
                loginError.textContent = '';
            }
        };

        window.showForgotForm = function(event) {
            if (event) event.preventDefault();
            if (forgotForm) forgotForm.style.display = 'flex';
            if (loginFields) loginFields.style.display = 'none';
            if (loginButtons) loginButtons.style.display = 'none';
            if (otpSection) otpSection.style.display = 'none';
            clearHeaderAlert();
            if (loginError) {
                loginError.classList.add('d-none');
                loginError.textContent = '';
            }
        };

        window.hideForgotForm = function(event) {
            if (event) event.preventDefault();
            window.showNormalLogin();
        };

        window.showTab = function(tab) {
            var isRegister = tab === 'register';

            if (loginBox) loginBox.style.display = isRegister ? 'none' : 'block';
            if (registerBox) registerBox.style.display = isRegister ? 'block' : 'none';

            if (!isRegister) {
                window.showNormalLogin();
            }

            if (loginTab) {
                loginTab.classList.toggle('active', !isRegister);
                loginTab.setAttribute('aria-selected', isRegister ? 'false' : 'true');
            }

            if (registerTab) {
                registerTab.classList.toggle('active', isRegister);
                registerTab.setAttribute('aria-selected', isRegister ? 'true' : 'false');
            }

            if (loginTab) {
                loginTab.style.backgroundColor = isRegister ? '#ffffff' : '#ff9800';
                loginTab.style.borderColor = '#ff9800';
                loginTab.style.color = isRegister ? '#000000' : '#ffffff';
            }

            if (registerTab) {
                registerTab.style.backgroundColor = isRegister ? '#ff9800' : '#ffffff';
                registerTab.style.borderColor = '#ff9800';
                registerTab.style.color = isRegister ? '#ffffff' : '#000000';
            }

            clearHeaderAlert();
            if (loginError) {
                loginError.classList.add('d-none');
                loginError.textContent = '';
            }
            resetRegisterMessages();
            clearRegisterFieldErrors();
        };

        if (loginSubmit) {
            loginSubmit.addEventListener('click', function(event) {
                event.preventDefault();

                var email = document.getElementById('header-login-email')?.value.trim();
                var password = document.getElementById('header-login-password')?.value;

                if (loginError) {
                    loginError.classList.add('d-none');
                    loginError.textContent = '';
                }

                if (!email || !password) {
                    showInlineMessage(loginError, 'Please enter both email and password.');
                    return;
                }

                setHeaderLoading(loginSubmit, true, 'Logging in...');

                fetch("{{ route('login.password') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        email: email,
                        password: password,
                    })
                })
                    .then(async function(response) {
                        var data = await response.json().catch(function() {
                            return {
                                success: false,
                                message: 'Unexpected server response.'
                            };
                        });

                        if (!response.ok || data.success === false) {
                            showInlineMessage(loginError, data.message || 'Login failed.');
                            return;
                        }

                        if (loginError) loginError.classList.add('d-none');
                        if (data.token) sessionStorage.setItem('auth_api_token', data.token);
                        if (data.user) sessionStorage.setItem('auth_user', JSON.stringify(data.user));

                        var modal = document.getElementById('authModal');
                        if (modal) {
                            var bsModal = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
                            bsModal.hide();
                        }

                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.reload();
                        }
                    })
                    .catch(function() {
                        showInlineMessage(loginError, 'Unable to reach authentication service.');
                    })
                    .finally(function() {
                        setHeaderLoading(loginSubmit, false);
                    });
            });
        }

        if (forgotSubmit) {
            forgotSubmit.addEventListener('click', function() {
                var forgotEmail = document.getElementById('header-forgot-email')?.value.trim();
                if (!forgotEmail) {
                    showInlineMessage(loginError, 'Please enter your email address.');
                    window.showNormalLogin();
                    return;
                }

                showInlineMessage(loginError, 'Password reset API is not configured yet.');
                window.showNormalLogin();
            });
        }

        if (registerForm) {
            registerForm.addEventListener('submit', function(event) {
                event.preventDefault();

                var firstName = document.getElementById('regFirstName')?.value.trim();
                var lastName = document.getElementById('regLastName')?.value.trim();
                var mobileNo = document.getElementById('regMobile')?.value.trim();
                var email = document.getElementById('regEmail')?.value.trim();
                var password = document.getElementById('regPassword')?.value;
                var submitButton = registerForm.querySelector('button[type="submit"]');

                resetRegisterMessages();
                clearRegisterFieldErrors();

                if (!firstName || !lastName || !mobileNo || !email || !password) {
                    showInlineMessage(registerError, 'All fields are required.');
                    return;
                }

                setHeaderLoading(submitButton, true, 'Registering...');

                fetch("{{ route('register.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                    body: JSON.stringify({
                        first_name: firstName,
                        last_name: lastName,
                        mobile_no: mobileNo,
                        email: email,
                        password: password,
                    })
                })
                    .then(async function(response) {
                        var data = await response.json().catch(function() {
                            return {
                                success: false,
                                message: 'Unexpected server response.'
                            };
                        });

                        if (!response.ok || data.success === false) {
                            if (data.errors && typeof data.errors === 'object') {
                                Object.keys(data.errors).forEach(function(key) {
                                    var fieldMap = {
                                        first_name: ['regFirstName', 'regFirstNameError'],
                                        last_name: ['regLastName', 'regLastNameError'],
                                        mobile_no: ['regMobile', 'regMobileError'],
                                        email: ['regEmail', 'regEmailError'],
                                        password: ['regPassword', 'regPasswordError'],
                                    };
                                    var mappedField = fieldMap[key];
                                    if (!mappedField) return;

                                    var input = document.getElementById(mappedField[0]);
                                    var error = document.getElementById(mappedField[1]);
                                    if (input) input.classList.add('is-invalid');
                                    if (error) error.textContent = data.errors[key][0];
                                });
                            }

                            showInlineMessage(registerError, data.message || 'Registration failed.');
                            return;
                        }

                        showInlineMessage(registerSuccess, data.message || 'Registration successful! You can now log in.');
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            window.location.reload();
                        }
                    })
                    .catch(function() {
                        showInlineMessage(registerError, 'Unable to reach registration service.');
                    })
                    .finally(function() {
                        setHeaderLoading(submitButton, false);
                    });
            });
        }

        window.showTab('login');
        window.showNormalLogin();

        // OTP input UX: auto-advance and backspace behavior
        otpInputs.forEach(function(input, index) {
            input.addEventListener('input', function(e) {
                const value = input.value.replace(/[^0-9]/g, '');
                input.value = value.slice(-1);

                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        if (sendBtn) {
            sendBtn.addEventListener('click', function() {
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
                }, function(data) {
                    showHeaderAlert(data.message || 'OTP sent successfully.', 'success');
                    if (mobileReadonly) mobileReadonly.value = mobile;
                    if (stepMobile && stepVerify) {
                        stepMobile.style.display = 'none';
                        stepVerify.style.display = 'block';
                    }
                    startHeaderResendCountdown(30);
                });

                setTimeout(function() {
                    setHeaderLoading(sendBtn, false);
                }, 600);
            });
        }

        if (verifyBtn) {
            verifyBtn.addEventListener('click', function() {
                const mobile = (mobileReadonly?.value || '').trim();
                const otp = getHeaderOtp();

                if (!otp || otp.length < 4) {
                    showHeaderAlert('Please enter the 4-digit OTP.', 'danger');
                    return;
                }

                setHeaderLoading(verifyBtn, true);

                headerPostJson("{{ route('login.otp.verify') }}", {
                    mobile_no: mobile,
                    country_code: '91',
                    otp: otp,
                    context: 'header',
                }, function(data) {
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

                setTimeout(function() {
                    setHeaderLoading(verifyBtn, false);
                }, 600);
            });
        }

        if (changeMobileBtn) {
            changeMobileBtn.addEventListener('click', function(event) {
                event.preventDefault();
                if (stepMobile && stepVerify) {
                    stepVerify.style.display = 'none';
                    stepMobile.style.display = 'block';
                    clearHeaderAlert();
                    clearHeaderOtp();
                }
            });
        }

        if (resendBtn) {
            resendBtn.addEventListener('click', function() {
                const mobile = (mobileReadonly?.value || '').trim();
                if (!mobile) {
                    showHeaderAlert('Mobile number is missing. Please go back and enter it again.', 'danger');
                    return;
                }

                setHeaderLoading(resendBtn, true);

                headerPostJson("{{ route('login.otp.resend') }}", {
                    mobile_no: mobile,
                    country_code: '91',
                    context: 'header',
                }, function(data) {
                    showHeaderAlert(data.message || 'OTP resent.', 'success');
                    startHeaderResendCountdown(30);
                });

                setTimeout(function() {
                    setHeaderLoading(resendBtn, false);
                }, 600);
            });
        }
    });


(function() {
    const searchInput = document.getElementById('searchInput');
    const suggestionsBox = document.getElementById('searchSuggestions');
    let debounceTimeout = null;

    function showSuggestions(items) {
        if (!items.length) {
            suggestionsBox.classList.remove('show');
            suggestionsBox.innerHTML = '';
            return;
        }
        suggestionsBox.innerHTML = items.map(item => {
            const imageUrl = item.image_url || '/assets/images/product-1.jpg';
            const price = item.total_price || item.price || '';
            const slug = item.slug || item.id;
            return `
                <a href="/products/${encodeURIComponent(slug)}" class="suggestion-item d-flex align-items-center gap-2 py-2 px-2 border-bottom text-decoration-none" data-id="${item.id}" style="transition:background 0.15s;">
                    <img src="${imageUrl}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; background: #fafafa;">
                    <div class="flex-grow-1 ms-1" style="min-width:0;">
                        <div class="fw-semibold text-dark text-truncate" style="font-size: 1rem;">${item.name || 'Product'}</div>
                        ${price ? `<div class="suggestion-price fw-bold text-success mt-1" style="font-size: 1.05rem;">₹${price}</div>` : ''}
                    </div>
                </a>
            `;
        }).join('');
        suggestionsBox.classList.add('show');
    // Add styles for professional suggestion dropdown
    const suggestionStyles = `
    .search-suggestions {
        box-shadow: 0 4px 24px rgba(0,0,0,0.10), 0 1.5px 4px rgba(0,0,0,0.04);
        border-radius: 0.6rem;
        background: #fff;
        border: 1px solid #eee;
        max-height: 420px;
        overflow-y: auto;
        min-width: 320px;
        padding: 0;
        margin-top: 0.25rem;
        z-index: 1002;
    }
    .search-suggestions .suggestion-item {
        cursor: pointer;
        border-bottom: 1px solid #f2f2f2;
        transition: background 0.13s;
    }
    .search-suggestions .suggestion-item:last-child {
        border-bottom: none;
    }
    .search-suggestions .suggestion-item:hover, .search-suggestions .suggestion-item:focus {
        background: #f7f7fa;
        text-decoration: none;
    }
    .search-suggestions .suggestion-price {
        color: #388e3c;
        font-weight: 600;
    }
    `;
    if (!document.getElementById('search-suggestion-styles')) {
        const styleTag = document.createElement('style');
        styleTag.id = 'search-suggestion-styles';
        styleTag.innerHTML = suggestionStyles;
        document.head.appendChild(styleTag);
    }
    }

    function fetchSuggestions(query) {
        fetch(`/api/product/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.status && Array.isArray(data.data)) {
                    showSuggestions(data.data.slice(0, 8));
                } else {
                    showSuggestions([]);
                }
            })
            .catch(() => showSuggestions([]));
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = searchInput.value.trim();
            clearTimeout(debounceTimeout);
            if (query.length < 2) {
                showSuggestions([]);
                return;
            }
            debounceTimeout = setTimeout(() => fetchSuggestions(query), 250);
        });
    }

    if (suggestionsBox) {
        suggestionsBox.addEventListener('mousedown', function(e) {
            const item = e.target.closest('.suggestion-item');
            if (item) {
                searchInput.value = item.querySelector('span').textContent;
                suggestionsBox.classList.remove('show');
                // Optionally redirect to product page:
                // window.location.href = `/products/${item.dataset.id}`;
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (suggestionsBox && !suggestionsBox.contains(e.target) && e.target !== searchInput) {
            suggestionsBox.classList.remove('show');
        }
    });
})();
</script>
@endpush
