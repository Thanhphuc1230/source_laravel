<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_order_status';

    protected $primaryKey = 'id_order_status';

    protected $guarded = [];
}
