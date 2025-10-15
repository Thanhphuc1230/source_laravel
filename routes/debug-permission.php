<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Debug routes for testing permissions
Route::prefix('debug-permissions')->middleware('web')->group(function () {
    
    // Test current user permissions
    Route::get('/test-user', function (Request $request) {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated']);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'email' => $user->email
            ],
            'roles' => $user->roles->pluck('name'),
            'total_permissions' => $user->getAllPermissions()->count(),
            'all_permissions' => $user->getAllPermissions()->pluck('name'),
            'direct_permissions' => $user->directPermissions->pluck('name'),
            'role_permissions' => $user->roles->load('permissions')->pluck('permissions')->flatten()->pluck('name')->unique(),
            'key_permissions' => [
                'product.view' => $user->hasPermission('product.view'),
                'cate_product.view' => $user->hasPermission('cate_product.view'),
                'news.view' => $user->hasPermission('news.view'),
                'system.view' => $user->hasPermission('system.view'),
                'has_any_product' => $user->hasAnyPermission(['cate_product.view', 'product.view'])
            ]
        ]);
    });

    // Test specific permission
    Route::get('/test-permission/{permission}', function ($permission) {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated']);
        }

        $hasPermission = $user->hasPermission($permission);
        
        return response()->json([
            'user_email' => $user->email,
            'permission' => $permission,
            'has_permission' => $hasPermission,
            'method_used' => 'hasPermission()'
        ]);
    });

    // Test middleware
    Route::get('/test-middleware/{permission}', function ($permission) {
        return response()->json([
            'message' => "Access granted to permission: {$permission}",
            'user_email' => auth()->user()->email
        ]);
    })->middleware('permission:{permission}');

    // Test multiple permissions
    Route::get('/test-multi/{permissions}', function ($permissions) {
        $permissionList = explode(',', $permissions);
        $user = auth()->user();
        $results = [];
        
        foreach ($permissionList as $perm) {
            $results[$perm] = $user->hasPermission(trim($perm));
        }
        
        return response()->json([
            'user_email' => $user->email,
            'permission_tests' => $results
        ]);
    });

    // List all available permissions
    Route::get('/list-permissions', function () {
        $permissions = \App\Models\Permission::orderBy('name')->get(['name', 'display_name']);
        $grouped = $permissions->groupBy(function ($item) {
            return explode('.', $item->name)[0];
        });
        
        return response()->json([
            'total_permissions' => $permissions->count(),
            'grouped_permissions' => $grouped
        ]);
    });
    
    // Test blade directive simulation
    Route::get('/test-blade/{permission}', function ($permission) {
        $user = auth()->user();
        $hasPermission = $user->hasPermission($permission);
        
        $html = $hasPermission ? 
            "<button class='btn btn-success'>Action Allowed</button>" : 
            "<span class='text-muted'>Action Hidden</span>";
            
        return response()->json([
            'permission' => $permission,
            'has_permission' => $hasPermission,
            'rendered_html' => $html
        ]);
    });
});