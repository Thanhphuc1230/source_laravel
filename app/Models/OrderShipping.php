<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShipping extends Model
{
    use Cachable, HasFactory;

    protected $table = 'tp_order_shipping';

    protected $primaryKey = 'id_order_shipping';

    protected $fillable = [
        'uuid_order_shipping',
        'f_name_order',
        'l_name_order',
        'phone',
        'email',
        'address',
        'note',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
