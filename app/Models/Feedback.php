<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    use HasFactory;

    protected $table = 'tp_feedback';

    protected $fillable = ['uuid', 'name', 'message', 'status', 'stt', 'image'];
}
