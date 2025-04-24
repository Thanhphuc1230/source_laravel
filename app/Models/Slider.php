<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $table = 'tp_sliders';
    protected $primaryKey = 'id_slider';
    protected $fillable = ['name_vn', 'link', 'status', 'image', 'stt', 'uuid'];
}
