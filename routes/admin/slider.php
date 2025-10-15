<?php

use App\Http\Controllers\Admin\SliderController;
use Illuminate\Support\Facades\Route;

Route::controller(SliderController::class)
    ->prefix('slider')
    ->name('slider.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:slider.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:slider.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:slider.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:slider.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:slider.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:slider.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:slider.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:slider.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:slider.edit');
    });
