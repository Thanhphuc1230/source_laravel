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
        'slug',
        'content_vn',
        'content_en',
        'footer',
        'stt',
        'image',
        'keywords',
        'description',
        'parent_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'footer' => 'boolean',
    ];
}
