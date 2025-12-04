<?php

namespace Database\Seeders;

use App\Models\MailTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Xác nhận đơn hàng',
                'subject' => 'Đơn hàng mới #{order_id}',
                'content' => '<h2>Thông báo đơn hàng mới #{order_id}</h2>

<p>Xin chào,</p>

<p>Một đơn hàng mới đã được đặt với thông tin chi tiết như sau:</p>

<h3>Thông tin khách hàng:</h3>
<ul>
    <li><strong>Họ tên:</strong> {customer_name}</li>
    <li><strong>Email:</strong> {customer_email}</li>
    <li><strong>Số điện thoại:</strong> {customer_phone}</li>
    <li><strong>Địa chỉ giao hàng:</strong> {customer_address}</li>
    <li><strong>Ghi chú:</strong> {order_note}</li>
</ul>

<h3>Thông tin đơn hàng:</h3>
<ul>
    <li><strong>Mã đơn hàng:</strong> #{order_id}</li>
    <li><strong>Ngày đặt hàng:</strong> {order_date}</li>
    <li><strong>Phương thức thanh toán:</strong> {payment_method}</li>
</ul>

<h3>Danh sách sản phẩm:</h3>
<table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr style="background-color: #f5f5f5;">
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        {products_list}
    </tbody>
</table>

<h3 style="margin-top: 20px;">Tổng tiền: <span style="color: #e74c3c; font-size: 20px;">{order_total}</span></h3>

<p>Vui lòng xử lý đơn hàng này trong thời gian sớm nhất.</p>

<p>Trân trọng,<br>
Hệ thống</p>',
                'variables' => ['order_id', 'customer_name', 'customer_email', 'customer_phone', 'customer_address', 'order_note', 'payment_method', 'order_total', 'order_date', 'products_list'],
                'type' => 'order',
                'is_active' => true,
            ],
            [
                'name' => 'Đơn hàng đã giao',
                'subject' => 'Đơn hàng #{order_id} đã được giao thành công',
                'content' => '<h2>Xin chào {customer_name},</h2>

<p>Đơn hàng của bạn đã được giao thành công!</p>

<p><strong>Thông tin đơn hàng:</strong></p>
<ul>
    <li>Mã đơn hàng: <strong>{order_id}</strong></li>
    <li>Tổng tiền: <strong>{order_total}</strong></li>
    <li>Ngày đặt: <strong>{order_date}</strong></li>
</ul>

<p>Cảm ơn bạn đã tin tưởng và mua sắm tại cửa hàng của chúng tôi. Chúng tôi rất mong được phục vụ bạn lần nữa!</p>

<p>Nếu sản phẩm có vấn đề gì, vui lòng liên hệ với chúng tôi trong vòng 7 ngày.</p>

<p>Trân trọng,<br>
Đội ngũ cửa hàng</p>',
                'variables' => ['customer_name', 'order_id', 'order_total', 'order_date'],
                'type' => 'order',
                'is_active' => true,
            ],
            [
                'name' => 'Phản hồi liên hệ',
                'subject' => 'Cảm ơn bạn đã liên hệ với chúng tôi',
                'content' => '<h2>Xin chào {contact_name},</h2>

<p>Cảm ơn bạn đã liên hệ với chúng tôi!</p>

<p>Chúng tôi đã nhận được thông tin liên hệ của bạn:</p>
<ul>
    <li>Họ tên: <strong>{contact_name}</strong></li>
    <li>Email: <strong>{contact_email}</strong></li>
    <li>Số điện thoại: <strong>{contact_phone}</strong></li>
    <li>Ngày liên hệ: <strong>{contact_date}</strong></li>
</ul>

<p><strong>Nội dung tin nhắn:</strong></p>
<p>{contact_message}</p>

<p>Chúng tôi sẽ phản hồi bạn trong thời gian sớm nhất, thường trong vòng 24 giờ.</p>

<p>Trân trọng,<br>
Đội ngũ hỗ trợ</p>',
                'variables' => ['contact_name', 'contact_email', 'contact_phone', 'contact_message', 'contact_date'],
                'type' => 'contact',
                'is_active' => true,
            ],
            [
                'name' => 'Thông báo liên hệ mới',
                'subject' => 'Thông báo: Có liên hệ mới từ {contact_name}',
                'content' => '<h2>Thông báo liên hệ mới</h2>

<p>Có một liên hệ mới từ khách hàng:</p>

<p><strong>Thông tin khách hàng:</strong></p>
<ul>
    <li>Họ tên: <strong>{contact_name}</strong></li>
    <li>Email: <strong>{contact_email}</strong></li>
    <li>Số điện thoại: <strong>{contact_phone}</strong></li>
    <li>Ngày liên hệ: <strong>{contact_date}</strong></li>
</ul>

<p><strong>Nội dung tin nhắn:</strong></p>
<p>{contact_message}</p>

<p>Vui lòng kiểm tra và xử lý liên hệ này.</p>',
                'variables' => ['contact_name', 'contact_email', 'contact_phone', 'contact_message', 'contact_date'],
                'type' => 'contact',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            MailTemplate::firstOrCreate(['name' => $template['name'], 'type' => $template['type']], $template);
        }
    }
}
