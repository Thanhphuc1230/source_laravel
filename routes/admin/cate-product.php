<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CateProductController;

Route::controller(CateProductController::class)
    ->prefix('cate_product')
    ->name('cate_product.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/status/{uuid}/{status}/{name}', 'status')->name('status');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit');
        Route::post('/update/{uuid}', 'update')->name('update');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
    });
