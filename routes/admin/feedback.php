<?php

use App\Http\Controllers\Admin\FeedBackController;
use Illuminate\Support\Facades\Route;

Route::controller(FeedBackController::class)
    ->prefix('feedback')
    ->name('feedback.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:feedback.view');
        Route::get('/create', 'create')->name('create')->middleware('permission:feedback.view'); // Feedback usually no create
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:feedback.edit');
        Route::post('/store', 'store')->name('store')->middleware('permission:feedback.edit');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:feedback.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:feedback.edit');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:feedback.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:feedback.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:feedback.edit');
    });
