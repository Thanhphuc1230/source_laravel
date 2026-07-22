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
        'address_vn',
        'address_en',
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
        'name_en',
        'description_vn',
        'description_en',
        'keyword_vn',
        'keyword_en',
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
        return lang($this, 'name');
    }

    public function getMetaKeywordAttribute()
    {
        return lang($this, 'keyword');
    }

    public function getMetaDescriptionAttribute()
    {
        return lang($this, 'description');
    }
}
