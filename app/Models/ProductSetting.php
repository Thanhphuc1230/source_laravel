<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Lấy giá trị setting theo key
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->where('is_active', true)->first();

        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Cập nhật hoặc tạo mới setting
     */
    public static function set($key, $value, $type = 'text', $group = 'general', $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
                'is_active' => true
            ]
        );
    }

    /**
     * Chuyển đổi giá trị theo type
     */
    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'number':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            case 'boolean':
                return (bool) $value;
            default:
                return $value;
        }
    }

    /**
     * Scope theo group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope chỉ active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
