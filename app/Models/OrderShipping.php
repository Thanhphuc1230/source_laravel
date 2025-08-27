<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipping extends Model
{
    use HasFactory;

    protected $table = 'tp_order_shipping';

    protected $primaryKey = 'id_order_shipping';

    protected $guarded = [];
}
