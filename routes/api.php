<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\ProductSearchController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiCheckoutController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Api\ReviewController;
// Remove the 'api' prefix - Laravel adds it automatically in api.php
Route::prefix('cart')->middleware('cart.user.resolved')->group(function () {
    Route::post('add-to-cart', [CartController::class, 'addToCart']);
    Route::post('buy-now', [CartController::class, 'buyNow']);
    Route::get('count', [CartController::class, 'cartCount']);
    Route::post('update-quantity', [CartController::class, 'updateQuantity']);
    Route::post('delete-item', [CartController::class, 'deleteItem']);
    Route::get('/', [CartController::class, 'getCart']);
});

Route::prefix('wishlist')->middleware('cart.user.resolved')->group(function () {
    Route::post('toggle', [WishlistController::class, 'toggle']);
    Route::get('count', [WishlistController::class, 'count']);
    Route::post('check', [WishlistController::class, 'check']);
});

Route::get('coupons', [CouponController::class, 'index'])
    ->middleware('cart.user.resolved');

Route::prefix('checkout')->group(function () {
    Route::post('details', [ApiCheckoutController::class, 'fetchCheckoutDetails']);
    Route::post('place-order', [ApiCheckoutController::class, 'placeOrder']);
    Route::post('payment/create-razorpay-order', [ApiCheckoutController::class, 'createRazorpayOrder']);
    Route::post('payment/verify', [ApiCheckoutController::class, 'verifyRazorpayPayment']);
});


// Product details API for buyNow/checkout pre-fill
Route::get('products/{id}', [ProductController::class, 'show']);

// Product details by slug (new API)
Route::get('v1/product/details/{slug}', [ProductController::class, 'detailsBySlug']);

// Product search autocomplete
Route::get('product/search', ProductSearchController::class);

Route::post('/v1/login', [LoginController::class, 'login']);
Route::post('/v1/register', [RegisterController::class, 'register']);

// Review API
Route::get('v1/reviews', [ReviewController::class, 'index']);
Route::post('v1/reviews', [ReviewController::class, 'store']);
Route::get('v1/reviews/summary', [ReviewController::class, 'summary']);
