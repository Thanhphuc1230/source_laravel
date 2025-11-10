<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Font extends Model
{
    use HasFactory;

    protected $table = 'tp_fonts';

    protected $fillable = [
        'name',
        'family',
        'file_path',
        'type',
        'css_url',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Lấy full URL của font
    public function getFullUrlAttribute()
    {
        if ($this->type === 'google') {
            return $this->css_url;
        }
        return $this->file_path ? asset($this->file_path) : null;
    }
}
