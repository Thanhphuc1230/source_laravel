<?php

use App\Http\Controllers\Admin\CateProductController;
use Illuminate\Support\Facades\Route;

Route::controller(CateProductController::class)
    ->prefix('cate_product')
    ->name('cate_product.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:cate_product.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:cate_product.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:cate_product.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:cate_product.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:cate_product.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:cate_product.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:cate_product.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:cate_product.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:cate_product.edit');
    });
