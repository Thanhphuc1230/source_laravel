<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $data['cart'] = $this->getCartSession();
        if (count($data['cart']) == 0) {
            toast()->error(session('locale') == 'en' ? 'No product in cart' : 'Chưa có sản phẩm nào trong giỏ hàng');

            return redirect()->route('web.home');
        }

        return view('frontend.modules.cart.index', $data);
    }

    private function getCartSession()
    {
        return session()->get('cart', []);
    }

    public function addToCart(Request $request, $uuid, $quantity = 1)
    {
        if ($request->quantity) {
            $quantity = $request->quantity;
        }

        $product = Product::with('category')->where('tp_products.uuid', $uuid)->first();

        // Kiểm tra xem sản phẩm có tồn tại không
        if (! $product) {
            if (session('locale') == 'en') {
                toast()->error('Product not found');
            } else {
                toast()->error('Sản phẩm không tồn tại.');
            }

            return back();
        }

        $this->addToCartSession($request, $quantity, $product);

        if (session('locale') == 'en') {
            toast()->success('Added to cart successfully.');
        } else {
            toast()->success('Đã thêm vào giỏ hàng thành công.');
        }

        return redirect()->route('web.cart');
    }

    private function addToCartSession($request, $quantity, $product)
    {
        // Lưu sản phẩm vào session
        $cart = session()->get('cart', []);
        $found = false; // Biến để kiểm tra xem sản phẩm đã tồn tại hay chưa

        foreach ($cart as $key => $item) {
            // Kiểm tra xem sản phẩm có cùng attribute không
            if ($item['uuid'] === $product->uuid) {
                // Nếu có, cộng dồn số lượng
                $cart[$key]['qty'] += $quantity;
                $found = true; // Đánh dấu là đã tìm thấy sản phẩm
                break; // Thoát khỏi vòng lặp
            }
        }

        // Nếu chưa tìm thấy sản phẩm, thêm sản phẩm mới vào giỏ hàng
        if (! $found) {
            $cart[] = [
                // Thay đổi từ $cart[$product->id_product] sang $cart[] để thêm sản phẩm mới
                'stt' => count($cart) + 1,
                'id_product' => $product->id_product,
                'name_vn' => $product->name_vn,
                'name_en' => $product->name_en,
                'qty' => $quantity,
                'price' => $product->price,
                'price_old' => $product->price_old,
                'avatar' => $product->image,
                'uuid' => $product->uuid,
                'name_cate' => $product->category->name_vn,
                'slug' => $product->slug,
                'slug_cate' => $product->category->slug,
            ];
        }

        session()->put('cart', $cart);
    }

    public function updateCart(Request $request)
    {
        $this->updateCartSession($request);

        if (session('locale') == 'en') {
            toast()->success('Updated cart successfully.');
        } else {
            toast()->success('Cập nhật giỏ hàng thành công.');
        }

        return back();
    }

    private function updateCartSession($request)
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

        // Cập nhật giỏ hàng trong session
        session()->put('cart', $cart);
    }

    private function removeItemCartSession($uuid, $stt)
    {
        $cart = session()->get('cart', []);
        foreach ($cart as $key => $item) {
            if ($item['uuid'] === $uuid && $item['stt'] === $stt) {
                unset($cart[$key]); // Xóa sản phẩm
                session()->put('cart', $cart); // Cập nhật giỏ hàng trong session
                if (session('locale') == 'en') {
                    toast()->success('Removed item from cart successfully.');
                } else {
                    toast()->success('Xóa sản phẩm trong giỏ hàng thành công.');
                }

                return back();
            }
        }
    }
}
