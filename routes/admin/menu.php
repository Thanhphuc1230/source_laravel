<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MenuController;

Route::controller(MenuController::class)
    ->prefix('menu')
    ->name('menu.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/status/{uuid}/{status}', 'status')->name('status');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{uuid}', 'edit')->name('edit');
        Route::post('/update/{uuid}', 'update')->name('update');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
    });
