<?php

use App\Http\Controllers\Admin\CommentController;
use Illuminate\Support\Facades\Route;

Route::controller(CommentController::class)
    ->prefix('comment')
    ->name('comment.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status');
        Route::get('/edit/{uuid}', 'edit')->name('edit');
        Route::post('/update/{uuid}', 'update')->name('update');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder');
    });
