<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiCheckoutController;

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
