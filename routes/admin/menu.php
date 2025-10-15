<?php

use App\Http\Controllers\Admin\MenuController;
use Illuminate\Support\Facades\Route;

Route::controller(MenuController::class)
    ->prefix('menu')
    ->name('menu.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:menu.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:menu.create');
        Route::get('/status/{uuid}/{status}', 'status')->name('status')->middleware('permission:menu.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:menu.create');
        Route::get('/edit/{uuid}', 'edit')->name('edit')->middleware('permission:menu.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:menu.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:menu.delete');
    });
