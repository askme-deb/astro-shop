<?php

use App\Http\Controllers\Api\CouponController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OtpAuthController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->middleware('cart.user.resolved')->name('cart.index');

Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])
    ->middleware('cart.user.resolved')
    ->name('apply.coupon');
// Remove coupon endpoint
Route::post('remove-coupon', [CouponController::class, 'remove'])->middleware('cart.user.resolved');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/user-addresses', [CheckoutController::class, 'userAddresses'])
    ->name('checkout.user-addresses');
Route::post('/checkout/address-update', [CheckoutController::class, 'updateAddress'])
    ->name('checkout.address-update');
Route::post('/checkout/address-delete', [CheckoutController::class, 'deleteAddress'])
    ->name('checkout.address-delete');
Route::post('/checkout/address-default', [CheckoutController::class, 'setDefaultAddress'])
    ->name('checkout.address-default');
Route::post('/checkout/address-save', [CheckoutController::class, 'saveAddress'])
    ->name('checkout.address-save');
Route::post('/checkout/state-list', [CheckoutController::class, 'stateList'])
    ->name('checkout.state-list');
Route::post('/checkout/city-list', [CheckoutController::class, 'cityList'])
    ->name('checkout.city-list');

Route::middleware(['guest'])->group(function () {
    Route::post('/login/otp/request', [OtpAuthController::class, 'requestOtp'])
        ->middleware('throttle:otp')
        ->name('login.otp.request');

    Route::post('/login/otp/resend', [OtpAuthController::class, 'resendOtp'])
        ->middleware('throttle:otp')
        ->name('login.otp.resend');

    Route::post('/login/otp/verify', [OtpAuthController::class, 'verifyOtp'])
        ->middleware('throttle:otp')
        ->name('login.otp.verify');
});

Route::post('/logout', [OtpAuthController::class, 'logout'])
    ->middleware(['web'])
    ->name('logout');

