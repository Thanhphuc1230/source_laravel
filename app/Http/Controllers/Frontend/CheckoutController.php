<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\OrderRequest;
use App\Services\CheckoutService;
use RealRashid\SweetAlert\Facades\Alert;

class CheckoutController extends Controller
{
    protected $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function index()
    {
        if (!$this->checkoutService->canCheckout()) {
            Alert::error(
                app()->getLocale() == 'en' ? 'Empty Cart' : 'Giỏ hàng đang trống',
                app()->getLocale() == 'en' ? 'No product in cart' : 'Chưa có sản phẩm nào trong giỏ hàng'
            );

            return redirect()->route('web.cart');
        }

        return view('frontend.modules.checkout.index');
    }

    public function checkoutStore(OrderRequest $request)
    {
        try {
            $orderId = $this->checkoutService->processOrder($request);

            // Clear cart after successful order
            $this->checkoutService->clearCartAfterOrder();

            return redirect()->route('web.orderSuccess', $orderId);

        } catch (\Exception $e) {
            \Log::error('Checkout failed: ' . $e->getMessage());

            Alert::error(
                app()->getLocale() == 'en' ? 'Order Failed' : 'Đặt hàng thất bại',
                app()->getLocale() == 'en' ? 'Please try again' : 'Vui lòng thử lại'
            );

            return back()->withInput();
        }
    }

    public function orderSuccess($orderId)
    {
        $orderDetails = $this->checkoutService->getOrderDetails($orderId);

        if (!$orderDetails) {
            Alert::error(
                app()->getLocale() == 'en' ? 'Order Not Found' : 'Không tìm thấy đơn hàng'
            );

            return redirect()->route('web.home');
        }

        return view('frontend.modules.checkout.order_success', [
            'order' => $orderDetails
        ]);
    }
}
