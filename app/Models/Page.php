<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_pages';

    protected $primaryKey = 'id_page';

    protected $fillable = [
        'uuid',
        'name_vn',
        'name_en',
        'slug_vn',
        'slug_en',
        'content_vn',
        'content_en',
        'footer',
        'stt',
        'image_vn',
        'image_en',
        'keyword_vn',
        'keyword_en',
        'description_vn',
        'description_en',
        'parent_id',
    ];

    public function getSlugAttribute()
    {
        return $this->slug_vn ?: $this->slug_en;
    }

    public function getImageAttribute()
    {
        return $this->image_vn ?: $this->image_en;
    }

    public function getKeywordsAttribute()
    {
        return $this->keyword_vn ?: $this->keyword_en;
    }

    public function getDescriptionAttribute()
    {
        return $this->description_vn ?: $this->description_en;
    }

    protected $casts = [
        'status' => 'boolean',
        'footer' => 'boolean',
    ];
}
