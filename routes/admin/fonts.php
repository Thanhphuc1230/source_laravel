<?php

use App\Http\Controllers\Admin\FontController;
use Illuminate\Support\Facades\Route;

Route::controller(FontController::class)
    ->prefix('fonts')
    ->name('fonts.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:font.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:font.create');
        Route::post('/', 'store')->name('store')->middleware('permission:font.create');
        Route::get('/{id}/edit', 'edit')->name('edit')->middleware('permission:font.edit');
        Route::put('/{id}', 'update')->name('update')->middleware('permission:font.edit');
        Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:font.delete');
        Route::post('/destroy-all', 'destroyAll')->name('destroyAll')->middleware('permission:font.delete');
    });