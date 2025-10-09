<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::get('/debug-permissions', function() {
    $user = \App\Models\User::first();
    
    echo "<h3>Debug Permission System</h3>";
    
    // Check table structure
    echo "<h4>1. Table tp_user_permission:</h4>";
    $data = DB::table('tp_user_permission')->get();
    echo "Records count: " . $data->count() . "<br>";
    
    if ($data->count() > 0) {
        echo "<table border='1'>";
        echo "<tr><th>User ID</th><th>Permission ID</th><th>Created At</th></tr>";
        foreach ($data as $record) {
            echo "<tr>";
            echo "<td>{$record->user_id}</td>";
            echo "<td>{$record->permission_id}</td>";
            echo "<td>{$record->created_at}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>No data found in tp_user_permission table</p>";
    }
    
    // Check user relationships
    if ($user) {
        echo "<h4>2. User: {$user->fullname}</h4>";
        echo "Direct permissions count: " . $user->directPermissions()->count() . "<br>";
        echo "All permissions count: " . $user->getAllPermissions()->count() . "<br>";
        echo "Roles count: " . $user->roles()->count() . "<br>";
        
        echo "<h5>User's roles:</h5>";
        foreach ($user->roles as $role) {
            echo "- {$role->display_name} ({$role->name})<br>";
        }
        
        echo "<h5>User's direct permissions:</h5>";
        foreach ($user->directPermissions as $permission) {
            echo "- {$permission->display_name} ({$permission->name})<br>";
        }
    }
    
    return "Debug completed!";
});

Route::post('/debug-form', function(Request $request) {
    echo "<h3>Debug Form Submission</h3>";
    echo "<h4>All Request Data:</h4>";
    echo "<pre>" . print_r($request->all(), true) . "</pre>";
    
    echo "<h4>Assignment Type:</h4>";
    echo $request->input('assignment_type', 'NOT SET');
    
    echo "<h4>Roles:</h4>";
    echo "<pre>" . print_r($request->input('roles', []), true) . "</pre>";
    
    echo "<h4>Permissions:</h4>";
    echo "<pre>" . print_r($request->input('permissions', []), true) . "</pre>";
    
    return "Form debug completed!";
});

Route::get('/test-permission-save', function() {
    $user = \App\Models\User::find(1);
    
    echo "<h3>Test Direct Permission Save</h3>";
    
    // Test manually syncing some permissions
    $testPermissions = [1, 2, 3]; // Permission IDs
    
    echo "Testing syncDirectPermissions with IDs: " . implode(', ', $testPermissions) . "<br>";
    
    try {
        $user->syncDirectPermissions($testPermissions);
        echo "<p style='color: green;'>SUCCESS: Permissions synced!</p>";
        
        // Check result
        $count = $user->directPermissions()->count();
        echo "Direct permissions count after sync: {$count}<br>";
        
        foreach ($user->directPermissions as $perm) {
            echo "- {$perm->display_name} (ID: {$perm->id})<br>";
        }
        
    } catch (\Exception $e) {
        echo "<p style='color: red;'>ERROR: " . $e->getMessage() . "</p>";
    }
    
    return "Test completed!";
});