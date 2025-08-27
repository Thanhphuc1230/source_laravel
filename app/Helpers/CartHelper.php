<?php

function countCart()
{
    return count(session()->get('cart', [])) ?: 0;
}

function getCart()
{
    return session()->get('cart', []);
}

function getTotalCartSession()
{
    $cart = getCart();
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['qty'];
    }

    return $total;
}
