<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'tp_permissions';

    protected $fillable = [
        'name',
        'display_name',
        'group_name',
        'description'
    ];

    /**
     * Relationship với Role
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'tp_role_permission', 'permission_id', 'role_id');
    }

    /**
     * Lấy permissions theo group
     */
    public static function getByGroup()
    {
        return static::all()->groupBy('group_name');
    }

    /**
     * Scope lọc theo group
     */
    public function scopeOfGroup($query, $groupName)
    {
        return $query->where('group_name', $groupName);
    }
}
