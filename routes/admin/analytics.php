<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AnalyticController;

Route::controller(AnalyticController::class)
    ->prefix('analytics')
    ->name('analytics.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });
