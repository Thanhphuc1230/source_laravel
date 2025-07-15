<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\OrderStatus;
use App\Models\OrderShipping;
use App\Models\OrderProduct;

class OrderController extends BaseController
{
    protected $module,$model,$nameItem,$imageFolder;
    public function __construct($imageFolder = 'order')
    {
        $this->module = 'order';
        $this->nameItem = 'Đơn Hàng';
        $this->imageFolder = $imageFolder;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(Request $request)
    {
        $query = DB::table('tp_order_shipping')
            ->join('tp_order_status', 'tp_order_shipping.id_order_shipping', '=', 'tp_order_status.shipping_id')
            ->select(
                'tp_order_shipping.email', 
                'tp_order_shipping.phone', 
                'tp_order_shipping.f_name_order',
                'tp_order_shipping.l_name_order',
                'tp_order_status.total', 
                'tp_order_status.status', 
                'tp_order_status.payment_method', 
                'tp_order_status.uuid_order_status', 
                'tp_order_status.created_at', 
                'tp_order_status.updated_at'
            )
            ->orderBy('tp_order_status.created_at', 'desc');

        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('tp_order_shipping.email', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tp_order_shipping.phone', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tp_order_shipping.f_name_order', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tp_order_shipping.l_name_order', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tp_order_status.uuid_order_status', 'LIKE', "%{$searchTerm}%");
            });
        }

        $data['list'] = $query->paginate(10);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    public function status($uuid, $status, $name)
    {
        $orderStatus = OrderStatus::where('uuid_order_status', $uuid)->first();
        
        if (!$orderStatus) {
            toast('Không tìm thấy ' . $this->nameItem, 'error');
            return redirect()->back();
        }

        $orderStatus->update([$name => $status]);

        $mess = $status == 1 ? 'Kích hoạt' : 'Tắt';
        toast($mess . ' ' . $this->nameItem . ' thành công', 'success');
        
        return redirect()->back();
    }

    public function edit($uuid)
    {
        // Thông tin và địa chỉ người đặt
        $data['user_order'] = DB::table('tp_order_status')
            ->join('tp_order_shipping', 'tp_order_status.shipping_id', '=', 'tp_order_shipping.id_order_shipping')
            ->select(
                'tp_order_shipping.l_name_order', 
                'tp_order_shipping.f_name_order', 
                'tp_order_shipping.phone',
                'tp_order_shipping.email', 
                'tp_order_shipping.address', 
                'tp_order_shipping.note',
                'tp_order_status.payment_method', 
                'tp_order_status.total', 
                'tp_order_status.id_order_status',
                'tp_order_status.status',
                'tp_order_status.uuid_order_status'
            )
            ->where('tp_order_status.uuid_order_status', $uuid)
            ->first();

        if (!$data['user_order']) {
            toast('Không tìm thấy ' . $this->nameItem, 'error');
            return back();
        }

        // Danh sách sản phẩm người dùng đặt
        $data['list'] = DB::table('tp_order_product')
            ->join('tp_products', 'tp_order_product.product_id', '=', 'tp_products.id_product')
            ->select(
                'tp_order_product.*',
                'tp_products.name_vn',
                'tp_products.image'
            )
            ->where('tp_order_product.order_status_id', $data['user_order']->id_order_status)
            ->get();

        $data['action'] = 'edit';
        $data['nameItem'] = $this->nameItem;
        
        return $this->view_admin('detail', $data);
    }

    public function destroy_order($uuid)
    {   
        $orderStatus = DB::table('tp_order_status')->where('uuid_order_status', $uuid)->first();
        if ($orderStatus) {
            // Xóa các sản phẩm trong đơn hàng
            DB::table('tp_order_product')
                ->where('order_status_id', $orderStatus->id_order_status)
                ->delete();
             // Xóa trạng thái đơn hàng
             DB::table('tp_order_status')
             ->where('uuid_order_status', $uuid)
             ->delete();
            // Xóa thông tin vận chuyển
            DB::table('tp_order_shipping')
                ->where('id_order_shipping', $orderStatus->shipping_id)
                ->delete();
            
            toast('Xóa ' . $this->nameItem . ' thành công', 'success');
            return back();
        } else {
            toast('Không tìm thấy ' . $this->nameItem, 'error');
            return back();
        }
    }
}
