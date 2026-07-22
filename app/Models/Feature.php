<?php

namespace App\Models;

use App\Traits\AutoImagePathsTrait;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use AutoImagePathsTrait, Cachable, HasFactory;

    protected $table = 'tp_features';

    protected $primaryKey = 'id_feature';

    protected $fillable = ['title_vn', 'title_en', 'content_vn', 'content_en', 'image', 'stt', 'uuid', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
