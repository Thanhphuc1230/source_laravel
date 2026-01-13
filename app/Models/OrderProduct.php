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

    protected $fillable = [
        'uuid_order_product',
        'order_status_id',
        'product_id',
        'quantity',
        'price',
        'attribute',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'integer',
    ];

    /**
     * Get the product for this order item
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

    /**
     * Get the order status for this order product
     */
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id_order_status');
    }
}
