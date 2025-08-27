<?php

use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(ProfileController::class)
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::post('/update/{uuid}', 'update')->name('update');
        Route::post('/change_password', 'changePassword')->name('changePassword');
    });
