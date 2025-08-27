<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    use HasFactory;

    protected $table = 'tp_analytics';

    protected $primaryKey = 'id';

    protected $fillable = [
        'visit_date',
        'visit_count',
    ];
}
