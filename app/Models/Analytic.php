<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_analytics';

    protected $primaryKey = 'id';

    protected $fillable = [
        'visit_date',
        'visit_count',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];
}
