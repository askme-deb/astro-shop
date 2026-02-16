<?php

use App\Http\Controllers\Api\CartController;
use Illuminate\Support\Facades\Route;

// Remove the 'api' prefix - Laravel adds it automatically in api.php
Route::prefix('cart')->group(function () {
    Route::post('add-to-cart', [CartController::class, 'addToCart']);
    Route::post('buy-now', [CartController::class, 'buyNow']);
    Route::get('count', [CartController::class, 'cartCount']);
});
