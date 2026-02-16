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
                    <a href="javascript:void(0)" onclick="toggleCart()">
                        <i class="fa fa-bag-shopping fs-5"></i>
                        <span id="cartCount"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <span id="cartCountValue">0</span>
                        </span>
                        <div class="icon-text">CART</div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                fetch('/api/cart/count')
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.status && typeof data.count !== 'undefined') {
                                            document.getElementById('cartCountValue').textContent = data.count;
                                        }
                                    })
                                    .catch(() => {
                                        document.getElementById('cartCountValue').textContent = 0;
                                    });
                            });
                        </script>
                    </a>

                    <!-- Mini Cart -->
                    <div class="mini-cart shadow" id="miniCart">
                        <h6 class="fw-bold mb-3">Shopping Cart</h6>

                        <!-- Item -->
                        <div class="cart-item d-flex gap-2 mb-3">
                            <img src="{{ asset('assets/images/product_10.png') }}" class="rounded" alt="">
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-semibold">Diamond Ring</p>
                                <small>Qty: 1</small>
                                <p class="mb-0 text-muted">₹4,999</p>
                            </div>
                            <button class="btn btn-sm text-danger" onclick="removeCartItem(this)">×</button>
                        </div>

                        <!-- Item -->
                        <div class="cart-item d-flex gap-2 mb-3">
                            <img src="{{ asset('assets/images/product_10.png') }}" class="rounded" alt="">
                            <div class="flex-grow-1">
                                <p class="mb-0 fw-semibold">Gold Necklace</p>
                                <small>Qty: 1</small>
                                <p class="mb-0 text-muted">₹2,499</p>
                            </div>
                            <button class="btn btn-sm text-danger" onclick="removeCartItem(this)">×</button>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fw-semibold">
                            <span>Total</span>
                            <span id="cartTotal">₹7,498</span>
                        </div>

                        <a href="#" class="btn btn-dark w-100 mt-3">View Cart</a>
                        <a href="#" class="btn btn-outline-dark w-100 mt-2">Checkout</a>
                    </div>
                </div>

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
