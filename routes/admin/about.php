<?php

use App\Http\Controllers\Admin\AboutController;
use Illuminate\Support\Facades\Route;

Route::controller(AboutController::class)
    ->prefix('about')
    ->name('about.')
    ->group(function () {
        Route::get('/', 'index')->name('index')->middleware('permission:about.view');
        Route::get('/edit/{uuid}/{page}', 'edit')->name('edit')->middleware('permission:about.edit');
        Route::post('/update/{uuid}', 'update')->name('update')->middleware('permission:about.edit');
    });
