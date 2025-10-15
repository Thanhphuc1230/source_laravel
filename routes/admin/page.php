<?php

use App\Http\Controllers\Admin\PageController;
use Illuminate\Support\Facades\Route;

Route::controller(PageController::class)
    ->prefix('page')
    ->name('page.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:page.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:page.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:page.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:page.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:page.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:page.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:page.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:page.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:page.edit');
    });
