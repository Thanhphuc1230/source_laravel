<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_brands';

    protected $primaryKey = 'id_brand';

    protected $fillable = ['name_vn', 'image', 'stt', 'uuid'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
