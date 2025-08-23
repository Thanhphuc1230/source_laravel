<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\RouteController;

Route::name('web.')
    ->middleware(['web', 'visit'])
    ->group(function () {
        // Handle all dynamic routes
        Route::get('{slug}.html', [RouteController::class, 'resolve'])
            ->where('slug', '[a-zA-Z0-9\-]+')
            ->name('resolve');
    });
