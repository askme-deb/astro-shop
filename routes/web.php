<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OtpAuthController;

Route::get('/', function () {
    return view('home');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->middleware('cart.user.resolved')->name('cart.index');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

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

