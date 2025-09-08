<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_order_product';

    protected $primaryKey = 'id_order_product';

    protected $guarded = [];
}
