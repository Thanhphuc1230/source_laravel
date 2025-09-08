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
        'slug',
        'intro_vn',
        'intro_en',
        'price',
        'price_old',
        'content_vn',
        'content_en',
        'image',
        'image_detail',
        'status',
        'hot',
        'stt',
        'keywords',
        'description',
        'category_id',
    ];

    public function cate()
    {
        return $this->belongsTo(CateProduct::class, 'category_id', 'id_cate_product');
    }
}
