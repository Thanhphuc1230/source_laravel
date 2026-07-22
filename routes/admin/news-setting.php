<?php

use App\Http\Controllers\Admin\NewsSettingController;
use Illuminate\Support\Facades\Route;

Route::controller(NewsSettingController::class)
    ->prefix('news-setting')
    ->name('news-setting.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:system.view');
        Route::post('/update', 'updateSettings')->name('update')->middleware('permission:system.edit');
    });
