<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_feedback';

    protected $fillable = ['uuid', 'name', 'message', 'stt', 'image'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
