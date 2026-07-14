<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API cho CKEditor lấy fonts
Route::get('/fonts/active', [App\Http\Controllers\Admin\FontController::class, 'getActiveFonts'])->name('api.fonts.active');

// Mobile Application API (Version 1)
Route::prefix('v1')->group(function () {
    Route::get('/system', [App\Http\Controllers\Api\V1\ApiController::class, 'getSystem']);
    Route::get('/sliders', [App\Http\Controllers\Api\V1\ApiController::class, 'getSliders']);
    Route::get('/categories', [App\Http\Controllers\Api\V1\ApiController::class, 'getCategories']);
    Route::get('/tours', [App\Http\Controllers\Api\V1\ApiController::class, 'getTours']);
    Route::get('/tours/{slug}', [App\Http\Controllers\Api\V1\ApiController::class, 'getTourDetail']);
    Route::get('/news', [App\Http\Controllers\Api\V1\ApiController::class, 'getNews']);
    Route::get('/news/{slug}', [App\Http\Controllers\Api\V1\ApiController::class, 'getNewsDetail']);
    Route::get('/pages/{id_page}', [App\Http\Controllers\Api\V1\ApiController::class, 'getPageDetail']);
    Route::post('/checkout', [App\Http\Controllers\Api\V1\ApiController::class, 'checkout']);
    Route::post('/contact', [App\Http\Controllers\Api\V1\ApiController::class, 'contact']);
});
