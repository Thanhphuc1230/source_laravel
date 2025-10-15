<?php

use App\Http\Controllers\Admin\UserRoleController;
use Illuminate\Support\Facades\Route;
// User Role Management Routes
Route::prefix('user-role')->name('user-role.')->group(function () {
    Route::get('/', [UserRoleController::class, 'index'])->name('index')->middleware('permission:user.view');
    Route::get('/{id}/edit', [UserRoleController::class, 'edit'])->name('edit')->middleware('permission:user.edit');
    Route::put('/{id}', [UserRoleController::class, 'updateRoles'])->name('update')->middleware('permission:user.edit');
    Route::post('/bulk-update', [UserRoleController::class, 'bulkUpdateRoles'])->name('bulk-update')->middleware('permission:user.edit');
    
    // Role management routes
    Route::get('/roles', [UserRoleController::class, 'roleIndex'])->name('roles.index')->middleware('permission:user.view');
    Route::get('/roles/create', [UserRoleController::class, 'roleCreate'])->name('roles.create')->middleware('permission:user.create');
    Route::post('/roles', [UserRoleController::class, 'roleStore'])->name('roles.store')->middleware('permission:user.create');
    Route::get('/roles/{id}/edit', [UserRoleController::class, 'roleEdit'])->name('roles.edit')->middleware('permission:user.edit');
    Route::put('/roles/{id}', [UserRoleController::class, 'roleUpdate'])->name('roles.update')->middleware('permission:user.edit');
    Route::delete('/roles/{id}', [UserRoleController::class, 'roleDestroy'])->name('roles.destroy')->middleware('permission:user.delete');
});