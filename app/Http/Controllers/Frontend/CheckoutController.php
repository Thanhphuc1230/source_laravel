<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\OrderRequest;
use App\Mail\AlertOrder;
use App\Models\OrderProduct;
use App\Models\OrderShipping;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class CheckoutController extends Controller
{
    public function index()
    {
        if (getCart() == 0) {
            Alert::error(app()->getLocale() == 'en' ? 'Empty Cart' : 'Giỏ hàng đang trống', app()->getLocale() == 'en' ? 'No product in cart' : 'Chưa có sản phẩm nào trong giỏ hàng');

            return redirect()->route('web.cart');
        }

        return view('frontend.modules.checkout.index');
    }

    public function checkoutStore(OrderRequest $request)
    {
        $shipping = new OrderShipping([
            'f_name_order' => $request->f_name_order,
            'l_name_order' => $request->l_name_order,
            'uuid_order_shipping' => Str::uuid(),
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'note' => $request->note,
        ]);
        $shipping->save();
        // Tình trạng đơn hàng
        $orderStatus = new OrderStatus([
            'uuid_order_status' => Str::uuid(),
            'shipping_id' => $shipping->id_order_shipping,
            'payment_method' => $request->payment_method,
            'total' => $request->total,
        ]);
        $orderStatus->save();

        $idOrderStatus = $orderStatus->id_order_status;
        $content_cart = getCart();
        // Sản phẩm đặt
        foreach ($content_cart as $product_content) {
            $v_data['uuid_order_product'] = Str::uuid();
            $v_data['order_status_id'] = $idOrderStatus;
            $v_data['product_id'] = $product_content['id_product'];
            $v_data['quantity'] = $product_content['qty'];
            $v_data['price'] = $product_content['price'];
            $v_data['created_at'] = new \DateTime;
            OrderProduct::create($v_data);
        }

        // send mail to admin
        $this->sendEmail('Order Success', 'Order Success'); // Sử dụng hàng đợi để gửi email

        return redirect()->route('web.orderSuccess', $idOrderStatus);
    }

    private function sendEmail($subject, $message)
    {
        $email = DB::table('tp_systems')->value('email_alert');
        Mail::to($email)->send(new AlertOrder($subject, $message));
    }

    public function orderSuccess()
    {
        // clear cart
        session()->forget('cart');

        return view('frontend.modules.checkout.order_success');
    }
}
