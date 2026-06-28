<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_news';

    protected $primaryKey = 'id_new';

    protected $fillable = [
        'uuid',
        'name_vn',
        'name_en',
        'intro_vn',
        'intro_en',
        'content_vn',
        'content_en',
        'category_id',
        'stt',
        'slug_vn',
        'slug_en',
        'image_vn',
        'image_en',
        'keyword_vn',
        'keyword_en',
        'description_vn',
        'description_en',
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
    ];

    public function cate()
    {
        return $this->belongsTo(CateNew::class, 'category_id', 'id_cate_new');
    }
}
