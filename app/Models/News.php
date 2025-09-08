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
        'status',
        'stt',
        'slug',
        'image',
        'keywords',
        'description',
        'created_at',
        'updated_at',
    ];

    public function cate()
    {
        return $this->belongsTo(CateNew::class, 'category_id', 'id_cate_new');
    }
}
