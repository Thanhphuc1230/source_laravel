<?php

use App\Http\Controllers\Admin\ServiceController;
use Illuminate\Support\Facades\Route;

Route::controller(ServiceController::class)
    ->prefix('service')
    ->name('service.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:service.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:service.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:service.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:service.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:service.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:service.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:service.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:service.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:service.edit');
    });
