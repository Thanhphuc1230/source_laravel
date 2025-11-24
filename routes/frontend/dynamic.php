<?php

use App\Http\Controllers\Frontend\RouteController;
use Illuminate\Support\Facades\Route;

Route::name('web.')
    ->middleware(['web', 'visit'])
    ->group(function () {
        // Handle all dynamic routes with ID-slug format
        Route::get('{id}-{slug}.html', [RouteController::class, 'resolve'])
            ->where(['id' => '[0-9]+', 'slug' => '[a-zA-Z0-9\-]+'])
            ->name('resolve');
    });
