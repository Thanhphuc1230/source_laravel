<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class CartService
{
    /**
     * Get cart from session
     *
     * @return array
     */
    public function getCart(): array
    {
        return session()->get('cart', []);
    }

    /**
     * Check if cart is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return count($this->getCart()) === 0;
    }

    /**
     * Add product to cart
     *
     * @param Request $request
     * @param string $uuid
     * @param int $quantity
     * @return bool
     */
    public function addProduct(Request $request, string $uuid, int $quantity = 1): bool
    {
        if ($request->quantity) {
            $quantity = $request->quantity;
        }
        
        // Validate quantity
        if ($quantity <= 0 || $quantity > 999) {
            return false;
        }

        $product = Product::with('cate')->where('uuid', $uuid)->first();

        if (!$product) {
            return false;
        }

        $this->addToCartSession($quantity, $product);
        return true;
    }

    /**
     * Add product to cart session
     *
     * @param int $quantity
     * @param Product $product
     * @return void
     */
    private function addToCartSession(int $quantity, Product $product): void
    {
        $cart = session()->get('cart', []);
        $found = false;

        foreach ($cart as $key => $item) {
            if ($item['uuid'] === $product->uuid) {
                $cart[$key]['qty'] += $quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = [
                'stt' => count($cart) + 1,
                'id_product' => $product->id_product,
                'name_vn' => $product->name_vn,
                'name_en' => $product->name_en,
                'qty' => $quantity,
                'price' => $product->price,
                'price_old' => $product->price_old,
                'avatar' => $product->image,
                'uuid' => $product->uuid,
                'name_cate' => $product->cate->name_vn ?? '',
                'slug' => $product->slug,
                'slug_cate' => $product->cate->slug ?? '',
            ];
        }

        session()->put('cart', $cart);
    }

    /**
     * Update cart quantities
     *
     * @param Request $request
     * @return void
     */
    public function updateCart(Request $request): void
    {
        $cart = session()->get('cart', []);

        foreach ($request->qty as $stt => $quantity) {
            foreach ($cart as &$item) {
                if ($item['stt'] == $stt) {
                    $item['qty'] = $quantity;
                    break;
                }
            }
        }

        session()->put('cart', $cart);
    }

    /**
     * Remove item from cart
     *
     * @param string $uuid
     * @param int $stt
     * @return bool
     */
    public function removeItem(string $uuid, int $stt): bool
    {
        $cart = session()->get('cart', []);

        foreach ($cart as $key => $item) {
            if ($item['uuid'] === $uuid && $item['stt'] === $stt) {
                unset($cart[$key]);
                session()->put('cart', $cart);
                return true;
            }
        }

        return false;
    }

    /**
     * Clear entire cart
     *
     * @return void
     */
    public function clearCart(): void
    {
        session()->forget('cart');
    }

    /**
     * Get cart total items count
     *
     * @return int
     */
    public function getTotalItems(): int
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'qty'));
    }

    /**
     * Get cart total price
     *
     * @return float
     */
    public function getTotalPrice(): float
    {
        $cart = $this->getCart();
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        return $total;
    }
}