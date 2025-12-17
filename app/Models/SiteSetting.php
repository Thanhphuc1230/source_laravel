<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'type',
        'value',
        'group',
        'description',
    ];

    protected $casts = [
        'value' => 'json',
    ];
}
