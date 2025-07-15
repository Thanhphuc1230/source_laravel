<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateProduct extends Model
{
    use HasFactory;
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
        return $this->hasMany(Product::class, 'category_id', 'id_cate_product')->where('status', 1)->orderBy('stt', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(CateProduct::class, 'parent_id', 'id_cate_product');
    }
}
