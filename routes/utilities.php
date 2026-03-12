<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FileManagerController;

// Language switching
Route::group(['middleware' => 'web'], function () {
    Route::get('lang/{locale}', function ($locale = 'vn') {
        // Set default to 'vn'
        if (! in_array($locale, ['en', 'vn'])) {
            abort(404);
        }
        session()->put('locale', $locale);

        return redirect()->back();
    })->name('lang');
});

// Sitemap
Route::get('/sitemap.xml', function () {
    return response()->file(public_path('sitemap.xml'));
});

// Custom File Manager API Routes (with permission middleware)
// Package routes tại /filemanager được dùng cho CKEditor
// Custom routes này dùng cho API/tính năng tùy chỉnh với permission check
Route::group(['prefix' => 'admin/files', 'middleware' => ['web', 'auth', 'permission:system.view']], function () {
    Route::get('/', [FileManagerController::class, 'index'])->name('admin.files.index');
    Route::post('/upload', [FileManagerController::class, 'upload'])->name('admin.files.upload');
    Route::delete('/delete', [FileManagerController::class, 'delete'])->name('admin.files.delete');
    Route::post('/create-folder', [FileManagerController::class, 'createFolder'])->name('admin.files.create-folder');
    Route::get('/ckeditor', [FileManagerController::class, 'ckeditor'])->name('admin.files.ckeditor');
});
