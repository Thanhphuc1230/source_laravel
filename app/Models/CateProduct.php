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
        'avatar',
        'status',
        'stt',
    ];

    public function children()
    {
        return $this->hasMany(CateProduct::class, 'parent_id', 'id_cate_product');
    }
}
