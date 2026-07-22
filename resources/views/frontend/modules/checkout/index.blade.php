@extends('frontend.master')
@section('module', 'Thanh toán đặt hàng - ' . $web->meta_name)
@section('keywords', $web->meta_keyword)
@section('description', $web->meta_description)
@section('images', $web->logo)

@section('content')
    <div class="bg-gray-100 py-6 border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-2xs text-gray-500 flex items-center space-x-2">
            <a href="{{ route('web.home') }}" class="hover:text-emerald-950">Trang chủ</a>
            <i class="fa-solid fa-chevron-right text-3xs"></i>
            <a href="{{ route('web.cart') }}" class="hover:text-emerald-950">Giỏ hàng</a>
            <i class="fa-solid fa-chevron-right text-3xs"></i>
            <span class="text-gray-700 font-semibold">Thanh toán</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-2xl font-heading font-extrabold text-emerald-950 mb-8 flex items-center">
            <i class="fa-solid fa-credit-card text-emerald-800 mr-3"></i>
            <span>Thanh toán & Đặt hàng</span>
        </h1>

        <form action="{{ route('web.checkoutStore') }}" method="POST">
            @csrf
            <input type="hidden" name="total" value="{{ $total }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Billing Details Form (Left Column, col-span-2) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                        <h3 class="font-heading font-bold text-sm text-emerald-950 border-b border-gray-100 pb-3">
                            Thông tin người đặt hàng
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1">Họ <span class="text-red-500">*</span></label>
                                <input type="text" name="l_name_order" value="{{ old('l_name_order') }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 placeholder-gray-400 @error('l_name_order') is-invalid @enderror" placeholder="Nguyễn">
                                @error('l_name_order')
                                    <span class="text-red-550 text-3xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1">Tên <span class="text-red-500">*</span></label>
                                <input type="text" name="f_name_order" value="{{ old('f_name_order') }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 placeholder-gray-400 @error('f_name_order') is-invalid @enderror" placeholder="Văn An">
                                @error('f_name_order')
                                    <span class="text-red-550 text-3xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 placeholder-gray-400 @error('email') is-invalid @enderror" placeholder="an.nguyen@example.com">
                                @error('email')
                                    <span class="text-red-550 text-3xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 placeholder-gray-400 @error('phone') is-invalid @enderror" placeholder="09xxxxxxxx">
                                @error('phone')
                                    <span class="text-red-550 text-3xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1">Địa chỉ giao dịch <span class="text-red-500">*</span></label>
                            <input type="text" name="address" value="{{ old('address') }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 placeholder-gray-400 @error('address') is-invalid @enderror" placeholder="Số nhà, tên đường, quận/huyện, thành phố...">
                            @error('address')
                                <span class="text-red-550 text-3xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1">Ghi chú thêm</label>
                            <textarea name="note" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 placeholder-gray-400 @error('note') is-invalid @enderror" placeholder="Yêu cầu đặc biệt về lịch trình, ăn uống, phòng nghỉ...">{{ old('note') }}</textarea>
                            @error('note')
                                <span class="text-red-550 text-3xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Method Section -->
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm space-y-4">
                        <h3 class="font-heading font-bold text-sm text-emerald-950 border-b border-gray-100 pb-3">
                            Phương thức thanh toán
                        </h3>
                        @error('payment_method')
                            <span class="text-red-550 text-3xs block">{{ $message }}</span>
                        @enderror

                        <div class="space-y-3">
                            <label class="flex items-start p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all select-none">
                                <input type="radio" name="payment_method" value="bank_transfer" checked class="mt-1 mr-3 text-emerald-800 focus:ring-emerald-850">
                                <div class="space-y-0.5">
                                    <span class="text-xs font-bold text-emerald-950 block">Chuyển khoản ngân hàng</span>
                                    <span class="text-3xs text-gray-500 leading-normal block">Thực hiện thanh toán chuyển khoản qua tài khoản ngân hàng của chúng tôi để nhận vé ngay.</span>
                                </div>
                            </label>

                            <label class="flex items-start p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all select-none">
                                <input type="radio" name="payment_method" value="cash" class="mt-1 mr-3 text-emerald-800 focus:ring-emerald-850">
                                <div class="space-y-0.5">
                                    <span class="text-xs font-bold text-emerald-950 block">Thanh toán tại văn phòng (Tiền mặt)</span>
                                    <span class="text-3xs text-gray-500 leading-normal block">Đến trực tiếp văn phòng giao dịch của BASE TRAVEL để thanh toán tiền mặt.</span>
                                </div>
                            </label>

                            <label class="flex items-start p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all select-none">
                                <input type="radio" name="payment_method" value="card" class="mt-1 mr-3 text-emerald-800 focus:ring-emerald-850">
                                <div class="space-y-0.5">
                                    <span class="text-xs font-bold text-emerald-950 block">Thẻ tín dụng / Ghi nợ</span>
                                    <span class="text-3xs text-gray-500 leading-normal block">Thanh toán trực tiếp bằng thẻ Visa, Mastercard, JCB qua cổng thanh toán liên kết bảo mật.</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Order Review Column (Right Column) -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-md space-y-6">
                        <h3 class="font-heading font-extrabold text-sm text-emerald-950 border-b border-gray-100 pb-3">
                            Đơn hàng của bạn
                        </h3>

                        <!-- List items summary -->
                        <div class="space-y-3 divide-y divide-gray-50 max-h-[250px] overflow-y-auto pr-2">
                            @foreach($cart as $item)
                                <div class="flex items-center space-x-3 pt-3 first:pt-0">
                                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        <img src="{{ asset($item['avatar']) }}" alt="{{ $item['name_vn'] }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <h4 class="font-bold text-2xs text-emerald-950 leading-snug truncate">
                                            {{ $item['name_vn'] }}
                                        </h4>
                                        <span class="text-3xs text-gray-500 block">Số lượng: <strong class="text-emerald-950">{{ $item['qty'] }}</strong></span>
                                    </div>
                                    <span class="text-2xs font-extrabold text-emerald-950 flex-shrink-0">
                                        {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}đ
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Summary prices -->
                        <div class="space-y-3 text-xs border-t border-gray-100 pt-4">
                            <div class="flex items-center justify-between text-gray-500">
                                <span>Tạm tính</span>
                                <span class="font-bold text-emerald-950">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex items-center justify-between text-gray-500">
                                <span>Phí dịch vụ / VAT</span>
                                <span class="text-emerald-800 font-bold">Miễn phí</span>
                            </div>
                            <hr class="border-gray-100">
                            <div class="flex items-center justify-between text-sm">
                                <span class="font-bold text-emerald-950">Tổng thanh toán</span>
                                <span class="text-lg font-black text-red-650">{{ number_format($total, 0, ',', '.') }}đ</span>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="space-y-3">
                            <button type="submit" class="w-full bg-gold-500 hover:bg-gold-600 text-emerald-950 font-black py-4 rounded-xl transition-all duration-300 shadow-md hover:scale-102 flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-lock text-sm"></i>
                                <span>Xác nhận đặt hàng</span>
                            </button>
                            <a href="{{ route('web.cart') }}" class="w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-emerald-950 font-bold py-3 flex items-center justify-center space-x-2 rounded-xl transition-colors">
                                <i class="fa-solid fa-chevron-left text-3xs"></i>
                                <span>Quay lại giỏ hàng</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
