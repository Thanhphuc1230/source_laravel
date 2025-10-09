<?php

use App\Http\Controllers\Admin\UserRoleController;
use Illuminate\Support\Facades\Route;
// User Role Management Routes
Route::prefix('user-role')->name('user-role.')->group(function () {
    Route::get('/', [UserRoleController::class, 'index'])->name('index');
    Route::get('/{id}/edit', [UserRoleController::class, 'edit'])->name('edit');
    Route::put('/{id}', [UserRoleController::class, 'updateRoles'])->name('update');
    Route::post('/bulk-update', [UserRoleController::class, 'bulkUpdateRoles'])->name('bulk-update');
    
    // Role management routes
    Route::get('/roles', [UserRoleController::class, 'roleIndex'])->name('roles.index');
    Route::get('/roles/create', [UserRoleController::class, 'roleCreate'])->name('roles.create');
    Route::post('/roles', [UserRoleController::class, 'roleStore'])->name('roles.store');
    Route::get('/roles/{id}/edit', [UserRoleController::class, 'roleEdit'])->name('roles.edit');
    Route::put('/roles/{id}', [UserRoleController::class, 'roleUpdate'])->name('roles.update');
    Route::delete('/roles/{id}', [UserRoleController::class, 'roleDestroy'])->name('roles.destroy');
});