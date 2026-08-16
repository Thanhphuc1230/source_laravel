<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use AutoImagePathsTrait, Cachable, HasFactory;

    protected $table = 'tp_abouts';

    protected $primaryKey = 'id_about';

    protected $fillable = [
        'uuid',
        'name_vn',
        'name_en',
        'intro_vn',
        'intro_en',
        'content_vn',
        'content_en',
        'image',
        'link',
        'status',
        'stt',
        'stats',
    ];

    protected $casts = [
        'status' => 'boolean',
        'stats' => 'array',
    ];

    public function getNameAttribute()
    {
        return lang($this, 'name');
    }

    public function getIntroAttribute()
    {
        return lang($this, 'intro');
    }

    public function getContentAttribute()
    {
        return lang($this, 'content');
    }

    public function getImageAttribute($value)
    {
        return $this->resolveImageUrl($value);
    }
}
