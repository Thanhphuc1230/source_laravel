<?php

use App\Http\Controllers\Admin\FeatureController;
use Illuminate\Support\Facades\Route;

Route::controller(FeatureController::class)
    ->prefix('feature')
    ->name('feature.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:feature.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:feature.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:feature.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:feature.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:feature.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:feature.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:feature.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:feature.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:feature.edit');
    });