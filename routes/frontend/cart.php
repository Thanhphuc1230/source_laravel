<?php

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::name('web.')
    ->middleware(['web', 'visit'])
    ->group(function () {
        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::get('/add-to-cart/{uuid}/{quantity?}', [CartController::class, 'addToCart'])->name('addToCart');
        Route::post('/update-cart', [CartController::class, 'updateCart'])->name('updateCart');
        Route::get('/remove-item/{uuid}/{stt}', [CartController::class, 'removeItem'])->name('removeItem');

        // Checkout
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout-store', [CheckoutController::class, 'checkoutStore'])->name('checkoutStore');
        Route::get('/order-success', [CheckoutController::class, 'orderSuccess'])->name('orderSuccess');
    });
