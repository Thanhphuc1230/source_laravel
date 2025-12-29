<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasRoles
{
    /**
     * Relationship với Role
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'tp_role_user', 'user_id', 'role_id');
    }

    /**
     * Direct permission relationship (for custom permissions)
     */
    public function directPermissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'tp_user_permission', 'user_id', 'permission_id');
    }

    /**
     * Lấy tất cả permissions qua roles + direct permissions
     */
    public function permissions()
    {
        // Get permissions from roles
        $rolePermissions = $this->roles()->with('permissions')->get()
            ->flatMap(function ($role) {
                return $role->permissions;
            });

        // Get direct permissions
        $directPermissions = $this->directPermissions;

        // Merge and get unique permissions
        return $rolePermissions->merge($directPermissions)->unique('id');
    }

    /**
     * Alias for permissions() method
     */
    public function getAllPermissions()
    {
        return $this->permissions();
    }

    /**
     * Cache permissions 5 minutes (for development)
     */
    public function getPermissionsAttribute()
    {
        return $this->permissions()->pluck('name')->toArray();
    }

    /**
     * Gán role cho user
     */
    public function assignRole($roles): void
    {
        $roles = $this->getRoleIds($roles);
        $this->roles()->syncWithoutDetaching($roles);
        $this->forgetCachedPermissions();
    }

    /**
     * Gán direct permission cho user
     */
    public function givePermissionTo($permissions): void
    {
        $permissions = $this->getPermissionIds($permissions);
        $this->directPermissions()->syncWithoutDetaching($permissions);
        $this->forgetCachedPermissions();
    }

    /**
     * Sync direct permissions
     */
    public function syncDirectPermissions($permissions): void
    {
        $permissions = $this->getPermissionIds($permissions);
        
        // Prepare data with timestamps
        $syncData = [];
        foreach ($permissions as $permissionId) {
            $syncData[$permissionId] = [
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        $this->directPermissions()->sync($syncData);
        $this->forgetCachedPermissions();
    }

    /**
     * Xóa role
     */
    public function removeRole($roles): void
    {
        $roles = $this->getRoleIds($roles);
        $this->roles()->detach($roles);
        $this->forgetCachedPermissions();
    }

    /**
     * Đồng bộ roles
     */
    public function syncRoles($roles): void
    {
        $roles = $this->getRoleIds($roles);
        
        // Prepare data with timestamps
        $syncData = [];
        foreach ($roles as $roleId) {
            $syncData[$roleId] = [
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        $this->roles()->sync($syncData);
        $this->forgetCachedPermissions();
    }

    /**
     * Kiểm tra có role không
     */
    public function hasRole($roles): bool
    {
        if (is_string($roles)) {
            return $this->roles()->where('name', $roles)->exists();
        }

        if (is_array($roles)) {
            return $this->roles()->whereIn('name', $roles)->exists();
        }

        if ($roles instanceof Role) {
            return $this->roles()->where('id', $roles->id)->exists();
        }

        return false;
    }

    /**
     * Kiểm tra có tất cả roles không
     */
    public function hasAllRoles($roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        if (is_array($roles)) {
            $userRoles = $this->roles()->pluck('name')->toArray();
            return empty(array_diff($roles, $userRoles));
        }

        return false;
    }

    /**
     * Kiểm tra có permission không
     */
    public function hasPermission($permission): bool
    {
        if (is_string($permission)) {
            return in_array($permission, $this->getPermissionsAttributeAttribute());
        }

        if (is_array($permission)) {
            return !empty(array_intersect($permission, $this->getPermissionsAttributeAttribute()));
        }

        return false;
    }

    /**
     * Kiểm tra có tất cả permissions không
     */
    public function hasAllPermissions($permissions): bool
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        if (is_array($permissions)) {
            return empty(array_diff($permissions, $this->getPermissionsAttributeAttribute()));
        }

        return false;
    }

    /**
     * Kiểm tra có ít nhất 1 permission
     */
    public function hasAnyPermission($permissions): bool
    {
        if (is_string($permissions)) {
            return in_array($permissions, $this->getPermissionsAttributeAttribute());
        }

        if (is_array($permissions)) {
            return !empty(array_intersect($permissions, $this->getPermissionsAttributeAttribute()));
        }

        return false;
    }

    /**
     * Xóa cache permissions
     */
    public function forgetCachedPermissions(): void
    {
        // No cache to forget
    }

    /**
     * Chuyển đổi roles thành array IDs
     */
    private function getRoleIds($roles): array
    {
        if (is_numeric($roles)) {
            return [$roles];
        }

        if (is_array($roles)) {
            return collect($roles)->map(function ($role) {
                if (is_numeric($role)) {
                    return $role;
                }
                if (is_string($role)) {
                    $roleModel = Role::where('name', $role)->first();
                    return $roleModel ? $roleModel->id : null;
                }
                if ($role instanceof Role) {
                    return $role->id;
                }
                return null;
            })->filter()->toArray();
        }

        if (is_string($roles)) {
            $role = Role::where('name', $roles)->first();
            return $role ? [$role->id] : [];
        }

        if ($roles instanceof Role) {
            return [$roles->id];
        }

        return [];
    }

    /**
     * Chuyển đổi permissions thành array IDs
     */
    private function getPermissionIds($permissions): array
    {
        if (is_numeric($permissions)) {
            return [$permissions];
        }

        if (is_array($permissions)) {
            return collect($permissions)->map(function ($permission) {
                if (is_numeric($permission)) {
                    return $permission;
                }
                if (is_string($permission)) {
                    $permissionModel = Permission::where('name', $permission)->first();
                    return $permissionModel ? $permissionModel->id : null;
                }
                if ($permission instanceof Permission) {
                    return $permission->id;
                }
                return null;
            })->filter()->toArray();
        }

        if (is_string($permissions)) {
            $permission = Permission::where('name', $permissions)->first();
            return $permission ? [$permission->id] : [];
        }

        if ($permissions instanceof Permission) {
            return [$permissions->id];
        }

        return [];
    }

    /**
     * Get permissions attribute properly
     */
    public function getPermissionsAttributeAttribute()
    {
        return $this->permissions()->pluck('name')->toArray();
    }

    /**
     * Public method to get user permissions
     */
    public function getUserPermissions()
    {
        return $this->getPermissionsAttributeAttribute();
    }
}