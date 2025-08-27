<?php

use App\Models\Product;

function getProduct($product_id)
{
    $data['product'] = Product::where('id_product', $product_id)->first();

    return $data['product'];
}
