<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use AutoImagePathsTrait, Cachable, HasFactory;

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
        'footer' => 'boolean',
    ];
}
