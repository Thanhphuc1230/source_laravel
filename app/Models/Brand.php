<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use AutoImagePathsTrait, Cachable, HasFactory;

    protected $table = 'tp_brands';

    protected $primaryKey = 'id_brand';

    protected $fillable = ['name_vn', 'name_en', 'image_vn', 'image_en', 'stt', 'uuid', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
