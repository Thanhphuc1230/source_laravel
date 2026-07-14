<?php

use App\Http\Controllers\Admin\SystemController;
use Illuminate\Support\Facades\Route;

Route::controller(SystemController::class)
    ->prefix('system')
    ->name('system.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:system.view');
        Route::get('/clear-cache', 'clearCache')->name('clearCache')->middleware('permission:system.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:system.edit');
        Route::post('/update/{id}', 'update')->name('update')->middleware('permission:system.edit');
    });
