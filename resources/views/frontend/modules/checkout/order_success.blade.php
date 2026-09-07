@extends('frontend.master')
@section('module', 'Đặt hàng thành công - ' . $web->meta_name)
@section('keywords', $web->meta_keyword)
@section('description', $web->meta_description)
@section('images', $web->logo)

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-16 text-center space-y-6">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-emerald-50 text-emerald-800 rounded-full flex items-center justify-center mx-auto text-4xl shadow-sm border border-emerald-100 animate-bounce">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <div class="space-y-2">
            <span class="text-gold-600 text-xs font-extrabold uppercase tracking-widest">Cảm ơn bạn!</span>
            <h1 class="text-3xl font-heading font-black text-slate-900">Đặt Hàng Thành Công</h1>
            <p class="text-xs text-slate-500 max-w-md mx-auto">
                Đơn hàng của bạn đã được tiếp nhận thành công. Đội ngũ chuyên viên của {{ $web->name_vn ?? 'AURA LUXURY WATCHES' }} sẽ liên hệ lại với bạn trong thời gian sớm nhất.
            </p>
        </div>

        <!-- Order Information Card -->
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-md max-w-xl mx-auto text-left space-y-4">
            <h3 class="font-heading font-bold text-sm text-slate-900 border-b border-slate-100 pb-2">
                Thông tin đơn hàng #HT-{{ $order['order']->id_order_status }}
            </h3>

            <div class="grid grid-cols-2 gap-y-3 text-xs">
                <span class="text-slate-400">Khách hàng:</span>
                <span class="font-bold text-slate-900 text-right">{{ $order['shipping']->l_name_order }} {{ $order['shipping']->f_name_order }}</span>

                <span class="text-slate-400">Số điện thoại:</span>
                <span class="font-bold text-slate-900 text-right">{{ $order['shipping']->phone }}</span>

                <span class="text-slate-400">Email:</span>
                <span class="font-bold text-slate-900 text-right truncate">{{ $order['shipping']->email }}</span>

                <span class="text-slate-400">Địa chỉ giao hàng:</span>
                <span class="font-semibold text-slate-700 text-right leading-snug">{{ $order['shipping']->address }}</span>

                <span class="text-slate-400">Phương thức thanh toán:</span>
                <span class="font-bold text-slate-900 text-right">
                    @if($order['order']->payment_method == 'bank_transfer')
                        Chuyển khoản ngân hàng
                    @elseif($order['order']->payment_method == 'cash')
                        Thanh toán tiền mặt khi nhận hàng
                    @else
                        Thẻ tín dụng / Ghi nợ
                    @endif
                </span>

                <span class="text-slate-400 border-t border-slate-100 pt-2">Tổng thanh toán:</span>
                <span class="font-black text-slate-900 text-right border-t border-slate-100 pt-2 text-base">
                    {{ number_format($order['total'], 0, ',', '.') }}đ
                </span>
            </div>

            <!-- Bank transfer info if selected -->
            @if($order['order']->payment_method == 'bank_transfer')
                <div class="bg-amber-50/50 rounded-xl p-4 border border-amber-100 text-3xs space-y-2 mt-4">
                    <p class="font-bold text-slate-900 text-2xs uppercase tracking-wide"><i class="fa-solid fa-building-columns mr-1 text-gold-600"></i> Thông tin chuyển khoản:</p>
                    <div class="grid grid-cols-2 gap-y-1.5 text-slate-600">
                        <span>Tên ngân hàng:</span>
                        <strong class="text-slate-900 text-right">Vietcombank</strong>
                        
                        <span>Số tài khoản:</span>
                        <strong class="text-slate-900 text-right text-xs">1014567890</strong>
                        
                        <span>Chủ tài khoản:</span>
                        <strong class="text-slate-900 text-right">{{ $web->name_vn ?? 'AURA LUXURY WATCHES' }}</strong>

                        <span>Nội dung CK:</span>
                        <strong class="text-gold-600 text-right">HT{{ $order['order']->id_order_status }}</strong>
                    </div>
                </div>
            @endif
        </div>

        <div class="pt-4 flex justify-center space-x-4">
            <a href="{{ route('web.home') }}" class="bg-emerald-950 hover:bg-emerald-900 text-white font-bold text-xs uppercase tracking-wider px-6 py-3 rounded-lg shadow-md transition-colors">
                Quay lại trang chủ
            </a>
        </div>
    </div>
@endsection
