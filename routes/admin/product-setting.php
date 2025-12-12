<?php

use App\Http\Controllers\Admin\ProductSettingController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductSettingController::class)
    ->prefix('product-setting')
    ->name('product-setting.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:system.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:system.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:system.edit');
        Route::get('/show/{uuid}', 'show')->name('show')->middleware('permission:system.view');
        Route::get('/edit/{uuid}', 'edit')->name('edit')->middleware('permission:system.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:system.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:system.edit');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:system.edit');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:system.edit');
    });
