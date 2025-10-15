<?php

use Illuminate\Support\Facades\Route;

Route::get('/permissions-report', function () {
    $users = \App\Models\User::with(['roles.permissions', 'directPermissions'])->get();
    
    $report = [];
    
    foreach ($users as $user) {
        $allPermissions = $user->getAllPermissions();
        $rolePermissions = $user->roles->load('permissions')->pluck('permissions')->flatten()->pluck('name')->unique();
        $directPermissions = $user->directPermissions->pluck('name');
        
        $report[] = [
            'user' => [
                'email' => $user->email,
                'fullname' => $user->fullname,
                'level' => $user->level
            ],
            'roles' => $user->roles->pluck('name')->toArray(),
            'total_permissions' => $allPermissions->count(),
            'role_permissions_count' => $rolePermissions->count(),
            'direct_permissions_count' => $directPermissions->count(),
            'permissions' => $allPermissions->pluck('name')->sort()->values()->toArray()
        ];
    }
    
    return response()->json($report, 200, [], JSON_PRETTY_PRINT);
});