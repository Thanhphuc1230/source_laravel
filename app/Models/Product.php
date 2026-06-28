<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_products';

    protected $primaryKey = 'id_product';

    protected $fillable = [
        'uuid',
        'name_vn',
        'name_en',
        'slug_vn',
        'slug_en',
        'intro_vn',
        'intro_en',
        'price',
        'price_old',
        'content_vn',
        'content_en',
        'image_vn',
        'image_en',
        'image_detail',
        'stt',
        'keyword_vn',
        'keyword_en',
        'description_vn',
        'description_en',
        'category_id',
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
        'hot' => 'boolean',
        'price' => 'decimal:2',
        'price_old' => 'decimal:2',
    ];

    public function cate()
    {
        return $this->belongsTo(CateProduct::class, 'category_id', 'id_cate_product');
    }
}
