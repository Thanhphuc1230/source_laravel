<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateNew extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_cate_news';

    protected $primaryKey = 'id_cate_new';

    protected $fillable = [
        'uuid',
        'name_vn',
        'name_en',
        'slug_vn',
        'slug_en',
        'keyword_vn',
        'keyword_en',
        'description_vn',
        'description_en',
        'image_vn',
        'image_en',
        'stt',
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
        'home' => 'boolean',
    ];

    public function children()
    {
        return $this->hasMany(CateNew::class, 'parent_id', 'id_cate_news');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'category_id', 'id_cate_new')->where('status', 1)->orderBy('stt', 'asc');
    }
}
