<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_features';

    protected $primaryKey = 'id_feature';

    protected $fillable = ['title_vn', 'content_vn', 'status', 'image', 'stt', 'uuid'];
}
