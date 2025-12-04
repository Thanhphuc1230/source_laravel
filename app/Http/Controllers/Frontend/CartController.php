<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\RateLimitService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;
    protected $rateLimitService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->rateLimitService = RateLimitService::forCart();
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
        $ip = $request->ip();

        // Check rate limit
        if ($this->rateLimitService->isBlocked($ip)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => session('locale') == 'en' ? 'Too many requests. Please try again later.' : 'Quá nhiều yêu cầu. Vui lòng thử lại sau.'
                ]);
            }
            toast()->error(session('locale') == 'en' ? 'Too many requests. Please try again later.' : 'Quá nhiều yêu cầu. Vui lòng thử lại sau.');
            return back();
        }

        if ($request->quantity) {
            $quantity = $request->quantity;
        }

        $success = $this->cartService->addProduct($request, $uuid, $quantity);

        if (!$success) {
            $this->rateLimitService->incrementAttempts($ip);
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => session('locale') == 'en' ? 'Product not found' : 'Sản phẩm không tồn tại.'
                ]);
            }
            toast()->error(session('locale') == 'en' ? 'Product not found' : 'Sản phẩm không tồn tại.');
            return back();
        }

        // Clear attempts on success
        $this->rateLimitService->clearAttempts($ip);

        $totalItems = $this->cartService->getTotalItems();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => session('locale') == 'en' ? 'Added to cart successfully.' : 'Đã thêm vào giỏ hàng thành công.',
                'totalItems' => $totalItems
            ]);
        }

        toast()->success(session('locale') == 'en' ? 'Added to cart successfully.' : 'Đã thêm vào giỏ hàng thành công.');
        return redirect()->route('web.cart');
    }

    public function updateCart(Request $request)
    {
        $this->cartService->updateCart($request);

        // Return JSON response for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            $cart = $this->cartService->getCart();
            $totalItems = $this->cartService->getTotalItems();
            $totalPrice = $this->cartService->getTotalPrice();

            return response()->json([
                'success' => true,
                'message' => session('locale') == 'en' ? 'Updated cart successfully.' : 'Cập nhật giỏ hàng thành công.',
                'cart' => $cart,
                'totalItems' => $totalItems,
                'totalPrice' => $totalPrice,
                'formattedTotalPrice' => number_format($totalPrice, 0, ',', '.') . ' VNĐ'
            ]);
        }

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
