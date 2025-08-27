<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateNew extends Model
{
    use HasFactory;

    protected $table = 'tp_cate_news';

    protected $primaryKey = 'id_cate_new';

    protected $fillable = [
        'uuid',
        'name_vn',
        'slug',
        'keywords',
        'description',
        'image',
        'status',
        'stt',
        'home',
        'parent_id',
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
