@extends('frontend.master')
@section('module', lang($product_detail, 'name'))
@section('keywords', lang($product_detail, 'keyword'))
@section('description', lang($product_detail, 'description'))
@section('images', $product_detail->image_vn ?? $web->logo)

@section('content')
    @if(!empty($product_settings['banner_detail']))
        <!-- Product Detail Header Banner -->
        <div class="bg-slate-900 text-white py-14 text-center space-y-2 relative overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center opacity-40" style="background-image: url('{{ asset($product_settings['banner_detail']) }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/70 to-slate-900/40"></div>
            <div class="relative z-10 max-w-7xl mx-auto px-4 space-y-2">
                <div class="text-2xs text-gray-300 flex items-center justify-center space-x-2">
                    <a href="{{ route('web.home') }}" class="hover:text-white">Trang chủ</a>
                    <i class="fa-solid fa-chevron-right text-3xs"></i>
                    @if($product_detail->cate)
                        <a href="{{ route('web.resolve', ['slug' => $product_detail->cate->slug]) }}" class="hover:text-white">{{ lang($product_detail->cate, 'name') }}</a>
                        <i class="fa-solid fa-chevron-right text-3xs"></i>
                    @endif
                    <span class="text-gold-400 font-semibold truncate">{{ lang($product_detail, 'name') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-white max-w-4xl mx-auto leading-snug pt-2">
                    {{ lang($product_detail, 'name') }}
                </h1>
            </div>
        </div>
    @else
        <!-- Product Detail Header Breadcrumb -->
        <div class="bg-gray-100 py-4 border-b border-gray-200/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-2xs text-gray-500 flex items-center space-x-2">
                <a href="{{ route('web.home') }}" class="hover:text-emerald-950">Trang chủ</a>
                <i class="fa-solid fa-chevron-right text-3xs"></i>
                @if($product_detail->cate)
                    <a href="{{ route('web.resolve', ['slug' => $product_detail->cate->slug]) }}" class="hover:text-emerald-950">{{ lang($product_detail->cate, 'name') }}</a>
                    <i class="fa-solid fa-chevron-right text-3xs"></i>
                @endif
                <span class="text-gray-700 truncate font-semibold">{{ lang($product_detail, 'name') }}</span>
            </div>
        </div>
    @endif

    <!-- Product Detail Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Tour Specs & Content (col-span-2) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Title & Gallery -->
                <div class="space-y-4">
                    <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-emerald-950 leading-tight">
                        {{ lang($product_detail, 'name') }}
                    </h1>
                    
                    <!-- Main image placeholder/preview -->
                    <div class="relative rounded-2xl overflow-hidden bg-gray-100 shadow-md h-[300px] sm:h-[450px]">
                        <img src="{{ asset($product_detail->image_vn) }}" alt="{{ lang($product_detail, 'name') }}" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- 4 Round Icons Specs Widget -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-emerald-50/50 p-5 rounded-xl border border-emerald-100 shadow-3xs">
                    <!-- Thời gian -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-emerald-950 text-gold-500 rounded-full flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <span class="text-3xs text-gray-400 block uppercase font-bold tracking-wider">Thời gian</span>
                            <span class="text-xs font-extrabold text-emerald-950 block">4 Ngày 3 Đêm</span>
                        </div>
                    </div>
                    <!-- Điểm khởi hành -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-emerald-950 text-gold-500 rounded-full flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-plane-departure"></i>
                        </div>
                        <div>
                            <span class="text-3xs text-gray-400 block uppercase font-bold tracking-wider">Khởi hành</span>
                            <span class="text-xs font-extrabold text-emerald-950 block">TP. Hồ Chí Minh</span>
                        </div>
                    </div>
                    <!-- Ngày đi -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-emerald-950 text-gold-500 rounded-full flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <div>
                            <span class="text-3xs text-gray-400 block uppercase font-bold tracking-wider">Ngày đi</span>
                            <span class="text-xs font-extrabold text-emerald-950 block">Hàng ngày</span>
                        </div>
                    </div>
                    <!-- Phương tiện -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-emerald-950 text-gold-500 rounded-full flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-bus"></i>
                        </div>
                        <div>
                            <span class="text-3xs text-gray-400 block uppercase font-bold tracking-wider">Phương tiện</span>
                            <span class="text-xs font-extrabold text-emerald-950 block">Máy bay / Ô tô</span>
                        </div>
                    </div>
                </div>

                <!-- Intro / Highlights -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-2xs space-y-3">
                    <h3 class="font-heading font-extrabold text-base text-emerald-950 flex items-center">
                        <i class="fa-solid fa-star text-gold-500 mr-2"></i>
                        <span>Điểm nhấn hành trình</span>
                    </h3>
                    <p class="text-xs text-gray-600 leading-relaxed font-medium">
                        {{ lang($product_detail, 'intro') }}
                    </p>
                </div>

                <!-- Main Content HTML -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-2xs space-y-4">
                    <h3 class="font-heading font-extrabold text-base text-emerald-950 border-b border-gray-100 pb-3 flex items-center">
                        <i class="fa-solid fa-circle-info text-emerald-800 mr-2"></i>
                        <span>Chi tiết chương trình Tour</span>
                    </h3>
                    <div class="prose prose-sm max-w-none text-xs text-gray-700 leading-relaxed space-y-3">
                        {!! lang($product_detail, 'content') !!}
                    </div>
                </div>

                <!-- Customer Reviews (Dynamic Comments) -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-2xs space-y-6">
                    <h3 class="font-heading font-extrabold text-base text-emerald-950 border-b border-gray-100 pb-3 flex items-center">
                        <i class="fa-solid fa-comments text-emerald-800 mr-2"></i>
                        <span>Nhận xét từ khách hàng ({{ $comments->count() }})</span>
                    </h3>
                    
                    @if($comments->isNotEmpty())
                        <div class="space-y-4 divide-y divide-gray-50">
                            @foreach($comments as $comment)
                                <div class="pt-4 first:pt-0 space-y-2">
                                    <div class="flex items-center justify-between text-2xs">
                                        <span class="font-bold text-emerald-950">{{ $comment->fullname }}</span>
                                        <span class="text-gray-400">{{ $comment->created_at ? $comment->created_at->format('d/m/Y') : '' }}</span>
                                    </div>
                                    <div class="flex items-center text-gold-500 text-3xs">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="fa-solid fa-star"></i>
                                        @endfor
                                    </div>
                                    <p class="text-2xs text-gray-650 italic leading-relaxed">
                                        "{{ $comment->content }}"
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-2xs text-gray-400 text-center py-4">Chưa có bình luận đánh giá nào cho tour này.</p>
                    @endif
                </div>

            </div>

            <!-- Right Column: Sticky Booking / CTA box & Related products -->
            <div class="space-y-6">
                <!-- Sticky Booking Box -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-lg space-y-5 sticky top-24">
                    <div class="text-center space-y-1">
                        <span class="text-gray-400 text-3xs uppercase tracking-wider block font-bold">Giá trọn gói từ</span>
                        @if($product_detail->price_old > $product_detail->price)
                            <span class="text-xs text-gray-450 line-through block leading-none">{{ number_format($product_detail->price_old, 0, ',', '.') }}đ</span>
                        @endif
                        <span class="text-3xl font-heading font-black text-red-600 block leading-none">{{ number_format($product_detail->price, 0, ',', '.') }}đ</span>
                    </div>

                    <div class="space-y-3 pt-2">
                        <!-- Add to Cart AJAX Realtime Action Button -->
                        <button type="button" onclick="addToCartAjax('{{ $product_detail->uuid }}', this)" class="w-full bg-emerald-950 hover:bg-emerald-900 text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-md hover:scale-102 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span>Thêm vào giỏ hàng</span>
                        </button>
                        
                        <!-- Messenger CTA -->
                        <a href="https://m.me" target="_blank" class="w-full bg-[#1877F2] hover:bg-[#166fe5] text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-md hover:scale-102 flex items-center justify-center space-x-2">
                            <i class="fa-brands fa-facebook-messenger"></i>
                            <span>Tư vấn qua Fanpage</span>
                        </a>

                        <!-- Hotline Call CTA -->
                        <a href="tel:{{ $web->phone ?? '18006700' }}" class="w-full bg-gold-500 hover:bg-gold-600 text-emerald-950 font-bold py-3.5 rounded-xl transition-all duration-300 shadow-md hover:scale-102 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-phone"></i>
                            <span>Hotline: {{ $web->phone ?? '1800 6700' }}</span>
                        </a>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-3.5 border border-gray-100 text-3xs text-gray-500 space-y-2">
                        <p class="font-bold text-gray-600 uppercase border-b border-gray-200 pb-1.5 mb-1.5"><i class="fa-solid fa-circle-check text-emerald-800 mr-1"></i> Giá tour bao gồm:</p>
                        <p><i class="fa-solid fa-check text-emerald-800 mr-1"></i> Vé máy bay khứ hồi / Xe chất lượng cao</p>
                        <p><i class="fa-solid fa-check text-emerald-800 mr-1"></i> Khách sạn tiêu chuẩn 3 - 5 sao</p>
                        <p><i class="fa-solid fa-check text-emerald-800 mr-1"></i> Các bữa ăn tiêu chuẩn chương trình</p>
                        <p><i class="fa-solid fa-check text-emerald-800 mr-1"></i> Hướng dẫn viên phục vụ suốt tuyến</p>
                    </div>
                </div>

                <!-- Related Products Widget -->
                @if(isset($related_product) && $related_product->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-4">
                        <h3 class="font-heading font-bold text-sm text-emerald-950 border-b border-gray-100 pb-3 flex items-center">
                            <i class="fa-solid fa-route text-emerald-800 mr-2"></i>
                            <span>Tour tương tự</span>
                        </h3>
                        <div class="space-y-4">
                            @foreach($related_product as $rel)
                                <div class="flex items-center space-x-3 group">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        <a href="{{ route('web.resolve', ['slug' => lang($rel, 'slug')]) }}">
                                            <img src="{{ asset($rel->image_vn) }}" alt="{{ lang($rel, 'name') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </a>
                                    </div>
                                    <div class="space-y-0.5 min-w-0">
                                        <h4 class="font-bold text-2xs text-emerald-950 leading-snug line-clamp-2 hover:text-emerald-700 transition-colors">
                                            <a href="{{ route('web.resolve', ['slug' => lang($rel, 'slug')]) }}">
                                                {{ lang($rel, 'name') }}
                                            </a>
                                        </h4>
                                        <span class="text-xs font-black text-red-600 block">{{ number_format($rel->price, 0, ',', '.') }}đ</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
