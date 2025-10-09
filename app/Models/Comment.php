<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Comment extends Model
{
    use HasFactory, Cachable;

    protected $table = 'tp_comments';
    protected $primaryKey = 'id_comment';
    protected $fillable = ['uuid', 'name', 'email', 'content', 'id_post','type_post','status'];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
     * Scope for active comments
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for pending comments
     */
    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Scope for comments by post type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type_post', $type);
    }

    /**
     * Scope for comments by post ID
     */
    public function scopeByPost($query, $postId)
    {
        return $query->where('id_post', $postId);
    }

    /**
     * Scope for recent comments
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get the type post name
     */
    public function getTypePostNameAttribute()
    {
        return match($this->type_post) {
            1 => 'Tin tức',
            2 => 'Sản phẩm', 
            3 => 'Trang nội dung',
            default => 'Khác'
        };
    }

    /**
     * Get all available post types
     */
    public static function getPostTypes()
    {
        return [
            1 => 'Tin tức',
            2 => 'Sản phẩm',
            3 => 'Trang nội dung'
        ];
    }
}