<?php

use App\Http\Controllers\Admin\GalleryController;
use Illuminate\Support\Facades\Route;

Route::controller(GalleryController::class)
    ->prefix('gallery')
    ->name('gallery.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:gallery.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:gallery.create');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:gallery.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:gallery.create');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:gallery.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:gallery.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:gallery.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:gallery.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:gallery.edit');
    });