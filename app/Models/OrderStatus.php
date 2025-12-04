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

    /**
     * Get the shipping information for this order
     */
    public function shipping()
    {
        return $this->belongsTo(OrderShipping::class, 'shipping_id', 'id_order_shipping');
    }

    /**
     * Get the products for this order
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_status_id', 'id_order_status');
    }
}
