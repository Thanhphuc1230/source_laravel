<?php

use App\Http\Controllers\Admin\FontController;
use Illuminate\Support\Facades\Route;

Route::controller(FontController::class)
    ->prefix('fonts')
    ->name('fonts.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('/destroy-all', 'destroyAll')->name('destroyAll');
    });