<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// User Management Routes
Route::prefix('user')->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index')->middleware('permission:user.view');
    Route::get('/create', [UserController::class, 'create'])->name('create')->middleware('permission:user.create');
    Route::post('/store', [UserController::class, 'store'])->name('store')->middleware('permission:user.create');
    Route::get('/{uuid}/edit', [UserController::class, 'edit'])->name('edit')->middleware('permission:user.edit');
    Route::put('/{uuid}', [UserController::class, 'update'])->name('update')->middleware('permission:user.edit');
    Route::delete('/{uuid}', [UserController::class, 'destroy'])->name('destroy')->middleware('permission:user.delete');
    Route::delete('/bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete')->middleware('permission:user.delete');
});