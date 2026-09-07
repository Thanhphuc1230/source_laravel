@extends('frontend.master')
@section('module', 'Giỏ hàng của bạn - ' . $web->meta_name)
@section('keywords', $web->meta_keyword)
@section('description', $web->meta_description)
@section('images', $web->logo)

@section('content')
    <div class="bg-gray-100 py-6 border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-2xs text-gray-500 flex items-center space-x-2">
            <a href="{{ route('web.home') }}" class="hover:text-emerald-950">Trang chủ</a>
            <i class="fa-solid fa-chevron-right text-3xs"></i>
            <span class="text-gray-700 font-semibold">Giỏ hàng của bạn</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-2xl font-heading font-extrabold text-emerald-950 mb-8 flex items-center">
            <i class="fa-solid fa-basket-shopping text-emerald-800 mr-3"></i>
            <span>Giỏ hàng của bạn</span>
        </h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items Column (col-span-2) -->
            <div class="lg:col-span-2 space-y-4">
                <form id="cart-form" action="{{ route('web.updateCart') }}" method="POST">
                    @csrf
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden divide-y divide-gray-100">
                        @foreach($cart as $item)
                            <div class="p-6 flex flex-col sm:flex-row items-center gap-6 cart-item-row" data-stt="{{ $item['stt'] }}" data-uuid="{{ $item['uuid'] }}">
                                <!-- Thumbnail -->
                                <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                    <img src="{{ asset($item['avatar']) }}" alt="{{ $item['name_vn'] }}" class="w-full h-full object-cover">
                                </div>

                                <!-- Description -->
                                <div class="flex-grow text-center sm:text-left space-y-1 min-w-0">
                                    <span class="text-4xs font-bold text-gold-600 bg-gold-50 px-2 py-0.5 rounded-md uppercase">
                                        {{ $item['name_cate'] }}
                                    </span>
                                    <h3 class="font-heading font-bold text-xs text-emerald-950 truncate">
                                        <a href="{{ route('web.resolve', ['slug' => $item['slug']]) }}" class="hover:text-emerald-700 transition-colors">
                                            {{ $item['name_vn'] }}
                                        </a>
                                    </h3>
                                    <div class="text-2xs text-gray-500 flex items-center justify-center sm:justify-start space-x-3 pt-0.5">
                                        <span>Đơn giá: <strong class="text-emerald-950">{{ number_format($item['price'], 0, ',', '.') }}đ</strong></span>
                                        @if($item['price_old'] > $item['price'])
                                            <span class="line-through text-gray-450">{{ number_format($item['price_old'], 0, ',', '.') }}đ</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Quantity Adjuster -->
                                <div class="flex items-center space-x-1 border border-gray-200 rounded-xl bg-gray-50 p-1 flex-shrink-0">
                                    <button type="button" onclick="adjustQty('{{ $item['stt'] }}', -1)" class="w-8 h-8 rounded-lg bg-white border border-gray-150 flex items-center justify-center hover:bg-gray-100 transition-colors text-emerald-950 font-bold">
                                        <i class="fa-solid fa-minus text-3xs"></i>
                                    </button>
                                    <input type="number" name="qty[{{ $item['stt'] }}]" id="qty-input-{{ $item['stt'] }}" value="{{ $item['qty'] }}" min="1" max="99" onchange="updateCartAjax()" class="w-10 text-center bg-transparent border-none text-xs font-extrabold text-emerald-950 focus:outline-none focus:ring-0 appearance-none">
                                    <button type="button" onclick="adjustQty('{{ $item['stt'] }}', 1)" class="w-8 h-8 rounded-lg bg-white border border-gray-150 flex items-center justify-center hover:bg-gray-100 transition-colors text-emerald-950 font-bold">
                                        <i class="fa-solid fa-plus text-3xs"></i>
                                    </button>
                                </div>

                                <!-- Item Subtotal -->
                                <div class="text-center sm:text-right flex-shrink-0 min-w-[100px]">
                                    <span class="text-3xs text-gray-400 block font-bold uppercase">Thành tiền</span>
                                    <span id="subtotal-{{ $item['stt'] }}" class="text-sm font-extrabold text-red-650 subtotal-value" data-price="{{ $item['price'] }}">
                                        {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}đ
                                    </span>
                                </div>

                                <!-- Delete button -->
                                <div class="flex-shrink-0">
                                    <a href="{{ route('web.removeItem', ['uuid' => $item['uuid'], 'stt' => $item['stt']]) }}" class="p-2.5 rounded-full bg-red-50 text-red-500 hover:bg-red-100 transition-colors flex items-center justify-center" title="Xóa sản phẩm">
                                        <i class="fa-solid fa-trash-can text-2xs"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <!-- Cart Summary Column (Right) -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-md space-y-6">
                    <h3 class="font-heading font-extrabold text-sm text-emerald-950 border-b border-gray-100 pb-3">
                        Tóm tắt đơn hàng
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between text-gray-500">
                            <span>Tạm tính</span>
                            <span id="summary-subtotal" class="font-bold text-emerald-950">
                                {{ number_format($totalPrice ?? array_sum(array_map(function($i) { return $i['price'] * $i['qty']; }, $cart)), 0, ',', '.') }}đ
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-gray-500">
                            <span>Phí vận chuyển / VAT</span>
                            <span class="text-emerald-800 font-bold">Miễn phí</span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-emerald-950">Tổng thanh toán</span>
                            <span id="summary-total" class="text-lg font-black text-red-650">
                                {{ number_format($totalPrice ?? array_sum(array_map(function($i) { return $i['price'] * $i['qty']; }, $cart)), 0, ',', '.') }}đ
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('web.checkout') }}" class="w-full bg-slate-900 hover:bg-gold-500 text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-md hover:scale-102 flex items-center justify-center space-x-2">
                            <span>Tiến hành thanh toán</span>
                            <i class="fa-solid fa-chevron-right text-3xs"></i>
                        </a>
                        <a href="{{ route('web.home') }}" class="w-full bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-900 font-bold py-3.5 rounded-xl transition-all duration-300 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                            <span>Tiếp tục mua sắm</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/cart-page.js') }}"></script>
@endpush
