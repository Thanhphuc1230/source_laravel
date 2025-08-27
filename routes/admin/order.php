<?php

use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::controller(OrderController::class)
    ->prefix('order')
    ->name('order.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/status/{uuid}/{status}/{name}', 'status')->name('status');
        Route::get('/edit/{uuid}', 'edit')->name('edit');
        Route::get('/destroy-order/{uuid}', 'destroy_order')->name('destroy_order');
    });
