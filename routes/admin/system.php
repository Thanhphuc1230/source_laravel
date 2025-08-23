<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SystemController;

Route::controller(SystemController::class)
    ->prefix('system')
    ->name('system.')
    ->middleware('admin.level:1')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::post('/update/{id}', 'update')->name('update');
    });
