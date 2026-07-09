<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateProduct extends Model
{
    use AutoImagePathsTrait, Cachable, HasFactory;

    protected $primaryKey = 'id_cate_product';

    protected $table = 'tp_cate_products';

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
        $locale = app()->getLocale();
        return $this->{'slug_' . $locale} ?: ($this->slug_vn ?: $this->slug_en);
    }

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->{'name_' . $locale} ?: ($this->name_vn ?: $this->name_en);
    }

    public function getImageAttribute()
    {
        $locale = app()->getLocale();
        return $this->{'image_' . $locale} ?: ($this->image_vn ?: $this->image_en);
    }

    public function getKeywordAttribute()
    {
        $locale = app()->getLocale();
        return $this->{'keyword_' . $locale} ?: ($this->keyword_vn ?: $this->keyword_en);
    }

    public function getKeywordsAttribute()
    {
        return $this->getKeywordAttribute();
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->{'description_' . $locale} ?: ($this->description_vn ?: $this->description_en);
    }

    protected $casts = [
        'status' => 'boolean',
    ];

    public function children()
    {
        return $this->hasMany(CateProduct::class, 'parent_id', 'id_cate_product');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id_cate_product')->where('status', 1)
        ->select('id_product','uuid', 'name_vn', 'slug_vn', 'slug_en', 'price','image_vn', 'image_en', 'intro_vn', 'category_id', 'status','hot','stt', 'created_at')
        ->orderBy('stt', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(CateProduct::class, 'parent_id', 'id_cate_product');
    }
}
