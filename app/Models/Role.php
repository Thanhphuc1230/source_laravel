<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
    use HasFactory;

    protected $table = 'tp_roles';

    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    /**
     * Relationship với Permission
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'tp_role_permission', 'role_id', 'permission_id');
    }

    /**
     * Relationship với User
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tp_role_user', 'role_id', 'user_id');
    }

    /**
     * Gán permissions cho role
     */
    public function givePermissionTo($permissions): void
    {
        $permissions = $this->getPermissionIds($permissions);
        $this->permissions()->syncWithoutDetaching($permissions);
        $this->forgetCachedPermissions();
    }

    /**
     * Thu hồi permissions
     */
    public function revokePermissionTo($permissions): void
    {
        $permissions = $this->getPermissionIds($permissions);
        $this->permissions()->detach($permissions);
        $this->forgetCachedPermissions();
    }

    /**
     * Đồng bộ permissions
     */
    public function syncPermissions($permissions): void
    {
        $permissions = $this->getPermissionIds($permissions);
        $this->permissions()->sync($permissions);
        $this->forgetCachedPermissions();
    }

    /**
     * Kiểm tra role có permission không
     */
    public function hasPermission($permissionName): bool
    {
        if (is_string($permissionName)) {
            return $this->permissions()->where('name', $permissionName)->exists();
        }

        if (is_array($permissionName)) {
            return $this->permissions()->whereIn('name', $permissionName)->exists();
        }

        return false;
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
            return $permissions;
        }

        if (is_string($permissions)) {
            $permission = Permission::where('name', $permissions)->first();
            return $permission ? [$permission->id] : [];
        }

        return [];
    }

    /**
     * Xóa cache permissions
     */
    private function forgetCachedPermissions(): void
    {
        // Xóa cache cho tất cả users có role này
        $this->users()->chunk(100, function ($users) {
            foreach ($users as $user) {
                Cache::forget("user.{$user->id}.permissions");
            }
        });
    }
}
