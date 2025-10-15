<?php

use App\Http\Controllers\Admin\CommentController;
use Illuminate\Support\Facades\Route;

Route::controller(CommentController::class)
    ->prefix('comment')
    ->name('comment.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:comment.view');
        Route::post('/status/{uuid}/{status}/{field}', 'status')->name('status')->middleware('permission:comment.moderate');
        Route::get('/edit/{uuid}', 'edit')->name('edit')->middleware('permission:comment.moderate');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:comment.moderate');
        Route::get('/destroy/{uuid}', 'destroy')->name('destroy')->middleware('permission:comment.delete');
        Route::post('/destroyAll', 'destroyAll')->name('destroyAll')->middleware('permission:comment.delete');
        Route::post('/update-stt/{uuid}', 'numericalOrder')->name('numericalOrder')->middleware('permission:comment.moderate');
    });
