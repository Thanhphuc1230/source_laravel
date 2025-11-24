<?php

use App\Http\Controllers\Admin\BrandController;
use Illuminate\Support\Facades\Route;

Route::controller(BrandController::class)
    ->prefix('brand')
    ->name('brand.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:brand.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:brand.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:brand.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:brand.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:brand.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:brand.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:brand.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:brand.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:brand.edit');
    });