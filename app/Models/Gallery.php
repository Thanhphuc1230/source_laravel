<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_galleries';

    protected $primaryKey = 'id_gallery';

    protected $fillable = [
        'name_vn',
        'status',
        'image',
        'stt',
        'uuid'
    ];
}
