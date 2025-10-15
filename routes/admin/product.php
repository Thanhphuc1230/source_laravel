<?php

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductController::class)
    ->prefix('product')
    ->name('product.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:product.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:product.create');
        Route::post('/status/{uuid}/{status}/{name}', 'status')->name('status')->middleware('permission:product.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:product.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:product.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:product.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:product.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:product.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:product.edit');
    });
