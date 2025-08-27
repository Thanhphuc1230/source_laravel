<?php

use App\Http\Controllers\Admin\AnalyticController;
use Illuminate\Support\Facades\Route;

Route::controller(AnalyticController::class)
    ->prefix('analytics')
    ->name('analytics.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });
