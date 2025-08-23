<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
Route::name('web.')
    ->middleware(['web', 'visit'])
    ->group(function () {
        // Cart
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::get('/add-to-cart/{uuid}/{quantity?}', [CartController::class, 'addToCart'])->name('addToCart');
        Route::post('/update-cart', [CartController::class, 'updateCart'])->name('updateCart');
        
        // Checkout
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout-store', [CheckoutController::class, 'checkoutStore'])->name('checkoutStore');
        Route::get('/order-success', [CheckoutController::class, 'orderSuccess'])->name('orderSuccess');
    });
