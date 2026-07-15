<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use AutoImagePathsTrait, Cachable, HasFactory;

    protected $table = 'tp_systems';

    protected $primaryKey = 'id_system';

    protected $fillable = [
        'email',
        'email_alert',
        'address',
        'phone',
        'footer_vn',
        'footer_en',
        'facebook',
        'youtube',
        'twitter',
        'instagram',
        'zalo',
        'favicon',
        'logo',
        'name_vn',
        'description',
        'keyword',
        'header_js',
        'body_js',
        'footer_js',
        'map',
        'contact_title_vn',
        'contact_desc_vn',
        'contact_title_en',
        'contact_desc_en',
    ];

    public function getMetaNameAttribute()
    {
        return $this->name_vn;
    }

    public function getMetaKeywordAttribute()
    {
        return $this->keyword;
    }

    public function getMetaDescriptionAttribute()
    {
        return $this->description;
    }
}
