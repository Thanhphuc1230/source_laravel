<?php

use App\Http\Controllers\Admin\ProductSettingController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductSettingController::class)
    ->prefix('product-setting')
    ->name('product-setting.')
    ->middleware('admin.level:1')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/show/{uuid}', 'show')->name('show');
        Route::get('/edit/{uuid}', 'edit')->name('edit');
        Route::post('/update/{uuid}', 'update')->name('update');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
        Route::post('/status/{uuid}/{status}/{name}', 'status')->name('status');
    });
