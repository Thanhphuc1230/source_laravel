<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use Cachable, HasFactory;

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
        'watermark',
        'name_vn',
        'description',
        'keyword',
        'header_js',
        'body_js',
        'footer_js',
        'map',
    ];
}
