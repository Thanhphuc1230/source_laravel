<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\ContactRequest;
use App\Models\Contact;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
    public function contact()
    {
        return view('frontend.modules.contact.index');
    }

    public function postContact(ContactRequest $request)
    {
        // Lấy địa chỉ IP của người dùng
        $ipAddress = $request->ip();

        // Lấy số lượng yêu cầu và thời gian yêu cầu từ session
        $requestData = Session::get('contact_request_data', []);
        $requestCount = $requestData[$ipAddress]['count'] ?? 0;
        $lastRequestTime = $requestData[$ipAddress]['last_request_time'] ?? now()->subMinutes(6);

        // Nếu thời gian yêu cầu cũ đã qua 5 phút, reset số lượng yêu cầu
        if (now()->diffInMinutes($lastRequestTime) > 5) {
            $requestCount = 0;
        }

        // Kiểm tra số lượng yêu cầu
        if ($requestCount >= 3) {
            // Nếu số lượng yêu cầu vượt quá 5, trả về thông báo lỗi
            Alert::error('Quá số lượng yêu cầu', 'Bạn đã gửi quá nhiều yêu cầu trong vòng 5 phút.');
            return back();
        }

        // Tiếp tục xử lý yêu cầu nếu không vượt quá giới hạn
        $data = $request->except('_token');
        $data['uuid'] = Str::uuid();
        $data['created_at'] = new \DateTime();
        $data['status'] = 0;
        // Lưu yêu cầu vào cơ sở dữ liệu
        Contact::create($data);

        // Cập nhật số lượng yêu cầu và thời gian yêu cầu vào session
        $requestData[$ipAddress] = [
            'count' => $requestCount + 1,
            'last_request_time' => now(),
        ];
        Session::put('contact_request_data', $requestData);

        Alert::success('Đã gửi yêu cầu thành công', 'Chúng tôi sẽ liên hệ sớm nhất có thể');
        return redirect()->route('web.contact');
    }
    
    public function postSubscribe(Request $request)
    {
        $data = $request->except('_token');
        $data['uuid'] = Str::uuid();
        $data['created_at'] = new \DateTime();

        Subscribe::create($data);
        Alert::success('Đã đăng ký nhận thông báo thành công', 'Chúng tôi sẽ liên hệ sớm nhất có thể');
        return back();
    }
}
