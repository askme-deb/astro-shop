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
                        if(!confirm('Remove this item?')) return;

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
                                fetchMiniCart(); // Refresh mini cart
                                updateCartCount(); // Refresh count
                                // If on cart page, maybe reload? For now, just refresh mini cart
                                if (window.location.pathname === '/cart') {
                                    window.location.reload();
                                }
                            } else {
                                alert('Failed to remove item');
                            }
                        })
                        .catch(err => console.error(err));
                    }
                </script>

                <div class="icon_warp">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#authModal">
                        <i class="fa fa-user fs-5"></i>
                        <div class="icon-text">LOGIN</div>
                    </a>
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

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-4" id="authTabs">
                <li class="nav-item">
                    <button class="nav-link active cdtr" onclick="showLogin()">Login</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link cdtr" onclick="showRegister()">Register</button>
                </li>
            </ul>

            <!-- LOGIN FORM -->
            <div id="loginForm">

                <h5 class="mb-3">Login to Your Account</h5>

                <!-- Username & Password -->
                <div id="passwordLogin">
                    <input type="text" class="form-control mb-3" placeholder="Username or Email">
                    <input type="password" class="form-control mb-3" placeholder="Password">
                    <button class="btn btn-dark w-100 mb-3 cdtr">Login</button>
                    <div class="text-center">
                        <a href="javascript:void(0)" onclick="showOTP()">Login with OTP</a>
                    </div>
                </div>

                <!-- OTP LOGIN -->
                <div id="otpLogin" style="display:none;">
                    <input type="text" class="form-control mb-3" placeholder="Enter Mobile Number">
                    <button class="btn btn-dark w-100 mb-3 cdtr" onclick="sendOTP()">Send OTP</button>

                    <div id="otpBox" style="display:none;">
                        <input type="text" class="form-control mb-3" placeholder="Enter OTP">
                        <button class="btn btn-success w-100">Verify & Login</button>
                    </div>

                    <div class="text-center mt-2">
                        <a href="javascript:void(0)" onclick="showPasswordLogin()">Login with Password</a>
                    </div>
                </div>

            </div>

            <!-- REGISTER FORM -->
            <div id="registerForm" style="display:none;">
                <h5 class="mb-3">Create Account</h5>
                <input type="text" class="form-control mb-3" placeholder="Full Name">
                <input type="email" class="form-control mb-3" placeholder="Email Address">
                <input type="text" class="form-control mb-3" placeholder="Mobile Number">
                <input type="password" class="form-control mb-3" placeholder="Password">
                <button class="btn btn-dark w-100 cdtr">Register</button>
            </div>

        </div>
    </div>
</div>
