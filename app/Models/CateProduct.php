<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateProduct extends Model
{
    use Cachable, HasFactory;

    protected $primaryKey = 'id_cate_product';

    protected $table = 'tp_cate_products';

    protected $fillable = [
        'uuid',
        'name_vn',
        'slug',
        'keywords',
        'description',
        'image',
        'status',
        'stt',
        'parent_id',
    ];

    public function children()
    {
        return $this->hasMany(CateProduct::class, 'parent_id', 'id_cate_product');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id_cate_product')->where('status', 1)
        ->select('uuid', 'name_vn', 'slug', 'price','image', 'intro_vn', 'category_id', 'status','hot','stt', 'created_at')
        ->orderBy('stt', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(CateProduct::class, 'parent_id', 'id_cate_product');
    }
}
