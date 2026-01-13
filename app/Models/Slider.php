<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_sliders';

    protected $primaryKey = 'id_slider';

    protected $fillable = ['name_vn', 'link', 'image', 'stt', 'uuid'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
