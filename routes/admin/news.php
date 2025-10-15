<?php

use App\Http\Controllers\Admin\NewsController;
use Illuminate\Support\Facades\Route;

Route::controller(NewsController::class)
    ->prefix('news')
    ->name('news.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:news.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:news.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:news.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:news.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:news.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:news.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:news.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:news.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:news.edit');
    });
