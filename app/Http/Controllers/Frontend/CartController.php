<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $data['cart'] = $this->cartService->getCart();

        if ($this->cartService->isEmpty()) {
            toast()->error(session('locale') == 'en' ? 'No product in cart' : 'Chưa có sản phẩm nào trong giỏ hàng');
            return redirect()->route('web.home');
        }

        return view('frontend.modules.cart.index', $data);
    }

    public function addToCart(Request $request, $uuid, $quantity = 1)
    {
        if ($request->quantity) {
            $quantity = $request->quantity;
        }

        $success = $this->cartService->addProduct($request, $uuid, $quantity);

        if (!$success) {
            toast()->error(session('locale') == 'en' ? 'Product not found' : 'Sản phẩm không tồn tại.');
            return back();
        }

        toast()->success(session('locale') == 'en' ? 'Added to cart successfully.' : 'Đã thêm vào giỏ hàng thành công.');
        return redirect()->route('web.cart');
    }

    public function updateCart(Request $request)
    {
        $this->cartService->updateCart($request);

        toast()->success(session('locale') == 'en' ? 'Updated cart successfully.' : 'Cập nhật giỏ hàng thành công.');
        return back();
    }

    public function removeItem(Request $request, $uuid, $stt)
    {
        $success = $this->cartService->removeItem($uuid, (int) $stt);

        if ($success) {
            toast()->success(session('locale') == 'en' ? 'Removed item from cart successfully.' : 'Xóa sản phẩm trong giỏ hàng thành công.');
        }

        return back();
    }
}
