@extends('frontend.master')
@section('module', lang($product_detail, 'name'))
@section('keywords', lang($product_detail, 'keyword'))
@section('description', lang($product_detail, 'description'))
@section('images', $product_detail->image_vn ?? $web->logo)

@section('content')
    <!-- Product Detail Header Breadcrumb -->
    <div class="bg-slate-100/70 py-3 border-b border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-2xs text-slate-500 flex items-center space-x-2">
            <a href="{{ route('web.home') }}" class="hover:text-gold-600 transition-colors">Trang chủ</a>
            <i class="fa-solid fa-chevron-right text-3xs text-slate-400"></i>
            @if($product_detail->cate)
                <a href="{{ route('web.resolve', ['slug' => $product_detail->cate->slug]) }}" class="hover:text-gold-600 transition-colors">{{ lang($product_detail->cate, 'name') }}</a>
                <i class="fa-solid fa-chevron-right text-3xs text-slate-400"></i>
            @endif
            <span class="text-slate-800 font-semibold truncate">{{ lang($product_detail, 'name') }}</span>
        </div>
    </div>

    <!-- Product Detail Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Specs & Content (col-span-2) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Title & Gallery -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        @if($product_detail->cate)
                            <span class="bg-slate-100 text-slate-700 text-3xs font-extrabold uppercase px-3 py-1 rounded-full border border-slate-200">
                                {{ lang($product_detail->cate, 'name') }}
                            </span>
                        @endif
                        @if($product_detail->hot)
                            <span class="bg-amber-500 text-white text-3xs font-extrabold uppercase px-3 py-1 rounded-full shadow-xs">
                                <i class="fa-solid fa-crown mr-1"></i> Hot Luxury
                            </span>
                        @endif
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-heading font-extrabold text-slate-900 leading-tight">
                        {{ lang($product_detail, 'name') }}
                    </h1>
                    
                    <!-- Main image container (aspect-square with subtle light bg and contain fit) -->
                    <div class="relative rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 p-6 flex items-center justify-center shadow-xs h-[320px] sm:h-[480px]">
                        <img src="{{ asset($product_detail->image_vn) }}" alt="{{ lang($product_detail, 'name') }}" class="max-h-full max-w-full object-contain hover:scale-105 transition-transform duration-500">
                    </div>
                </div>

                <!-- 4 Luxury Watch Specs Widget -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-slate-50/80 p-5 rounded-2xl border border-slate-100 shadow-3xs">
                    <!-- Xuất xứ -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white border border-slate-200 text-gold-600 rounded-xl flex items-center justify-center shadow-2xs">
                            <i class="fa-solid fa-gem text-sm"></i>
                        </div>
                        <div>
                            <span class="text-3xs text-slate-400 block uppercase font-bold tracking-wider">Xuất xứ</span>
                            <span class="text-xs font-extrabold text-slate-900 block">Thụy Sĩ / Swiss</span>
                        </div>
                    </div>
                    <!-- Bộ máy -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white border border-slate-200 text-gold-600 rounded-xl flex items-center justify-center shadow-2xs">
                            <i class="fa-solid fa-gear text-sm"></i>
                        </div>
                        <div>
                            <span class="text-3xs text-slate-400 block uppercase font-bold tracking-wider">Bộ máy</span>
                            <span class="text-xs font-extrabold text-slate-900 block">Automatic / Cơ</span>
                        </div>
                    </div>
                    <!-- Bảo hành -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white border border-slate-200 text-gold-600 rounded-xl flex items-center justify-center shadow-2xs">
                            <i class="fa-solid fa-shield-halved text-sm"></i>
                        </div>
                        <div>
                            <span class="text-3xs text-slate-400 block uppercase font-bold tracking-wider">Bảo hành</span>
                            <span class="text-xs font-extrabold text-slate-900 block">5 Năm Quốc Tế</span>
                        </div>
                    </div>
                    <!-- Tình trạng -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white border border-slate-200 text-gold-600 rounded-xl flex items-center justify-center shadow-2xs">
                            <i class="fa-solid fa-box-open text-sm"></i>
                        </div>
                        <div>
                            <span class="text-3xs text-slate-400 block uppercase font-bold tracking-wider">Tình trạng</span>
                            <span class="text-xs font-extrabold text-slate-900 block">Mới 100% Fullbox</span>
                        </div>
                    </div>
                </div>

                <!-- Intro / Highlights -->
                @if(!empty(lang($product_detail, 'intro')))
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-2xs space-y-3">
                    <h3 class="font-heading font-extrabold text-base text-slate-900 flex items-center">
                        <i class="fa-solid fa-star text-gold-500 mr-2"></i>
                        <span>Đặc điểm nổi bật</span>
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-medium">
                        {{ lang($product_detail, 'intro') }}
                    </p>
                </div>
                @endif

                <!-- Main Content HTML -->
                <div class="bg-white rounded-2xl border border-slate-100 p-6 sm:p-8 shadow-2xs space-y-4">
                    <h3 class="font-heading font-extrabold text-base text-slate-900 border-b border-slate-100 pb-3 flex items-center">
                        <i class="fa-solid fa-circle-info text-gold-600 mr-2"></i>
                        <span>Mô tả chi tiết sản phẩm</span>
                    </h3>
                    <div class="prose prose-slate max-w-none text-xs sm:text-sm text-slate-700 leading-relaxed space-y-3">
                        {!! lang($product_detail, 'content') !!}
                    </div>
                </div>

                <!-- Customer Reviews (Dynamic Comments) -->
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-2xs space-y-6">
                    <h3 class="font-heading font-extrabold text-base text-slate-900 border-b border-slate-100 pb-3 flex items-center">
                        <i class="fa-solid fa-comments text-gold-600 mr-2"></i>
                        <span>Đánh giá từ khách hàng ({{ $comments->count() }})</span>
                    </h3>
                    
                    @if($comments->isNotEmpty())
                        <div class="space-y-4 divide-y divide-slate-100">
                            @foreach($comments as $comment)
                                <div class="pt-4 first:pt-0 space-y-2">
                                    <div class="flex items-center justify-between text-2xs">
                                        <span class="font-bold text-slate-900">{{ $comment->fullname }}</span>
                                        <span class="text-slate-400">{{ $comment->created_at ? $comment->created_at->format('d/m/Y') : '' }}</span>
                                    </div>
                                    <div class="flex items-center text-amber-400 text-3xs">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="fa-solid fa-star"></i>
                                        @endfor
                                    </div>
                                    <p class="text-2xs text-slate-600 italic leading-relaxed">
                                        "{{ $comment->content }}"
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-2xs text-slate-400 text-center py-4">Chưa có đánh giá nào cho sản phẩm này.</p>
                    @endif
                </div>

            </div>

            <!-- Right Column: Sticky Booking / CTA box & Related products -->
            <div class="space-y-6">
                <!-- Sticky Booking Box -->
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-lg space-y-5 sticky top-24">
                    <div class="text-center space-y-1">
                        <span class="text-slate-400 text-3xs uppercase tracking-wider block font-bold">Giá niêm yết chính hãng</span>
                        @if($product_detail->price_old > $product_detail->price)
                            <span class="text-xs text-slate-400 line-through block leading-none">{{ number_format($product_detail->price_old, 0, ',', '.') }}đ</span>
                        @endif
                        <span class="text-2xl sm:text-3xl font-heading font-black text-slate-900 block leading-none mt-1">
                            {{ number_format($product_detail->price, 0, ',', '.') }}đ
                        </span>
                    </div>

                    <div class="space-y-3 pt-2">
                        <!-- Add to Cart AJAX Realtime Action Button -->
                        <button type="button" onclick="addToCartAjax('{{ $product_detail->uuid }}', this)" class="w-full bg-slate-900 hover:bg-gold-500 text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-md hover:scale-102 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span>Thêm vào giỏ hàng</span>
                        </button>
                        
                        <!-- Messenger CTA -->
                        @if(!empty($web->facebook))
                        <a href="{{ $web->facebook }}" target="_blank" class="w-full bg-[#1877F2] hover:bg-[#166fe5] text-white font-bold py-3 rounded-xl transition-all duration-300 shadow-xs hover:scale-102 flex items-center justify-center space-x-2 text-xs">
                            <i class="fa-brands fa-facebook-messenger"></i>
                            <span>Tư vấn qua Fanpage</span>
                        </a>
                        @endif

                        <!-- Hotline Call CTA -->
                        @if(!empty($web->phone))
                        <a href="tel:{{ $web->phone }}" class="w-full bg-amber-50 hover:bg-gold-500 text-gold-700 hover:text-white border border-gold-300/80 font-bold py-3 rounded-xl transition-all duration-300 shadow-xs hover:scale-102 flex items-center justify-center space-x-2 text-xs">
                            <i class="fa-solid fa-phone"></i>
                            <span>Hotline: {{ $web->phone }}</span>
                        </a>
                        @endif
                    </div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-3xs text-slate-600 space-y-2">
                        <p class="font-bold text-slate-800 uppercase border-b border-slate-200 pb-1.5 mb-1.5 flex items-center">
                            <i class="fa-solid fa-circle-check text-gold-600 mr-1.5 text-xs"></i> Đặc quyền & Cam kết:
                        </p>
                        <p class="flex items-center"><i class="fa-solid fa-check text-gold-600 mr-1.5"></i> 100% Đồng hồ chính hãng Thụy Sĩ cao cấp</p>
                        <p class="flex items-center"><i class="fa-solid fa-check text-gold-600 mr-1.5"></i> Bảo hành quốc tế 5 năm & Hỗ trợ kỹ thuật trọn đời</p>
                        <p class="flex items-center"><i class="fa-solid fa-check text-gold-600 mr-1.5"></i> Đầy đủ hộp sổ thẻ bảo hành & phụ kiện nguyên bản</p>
                        <p class="flex items-center"><i class="fa-solid fa-check text-gold-600 mr-1.5"></i> Miễn phí giao hàng VIP bảo mật & bảo hiểm toàn quốc</p>
                    </div>
                </div>

                <!-- Related Products Widget -->
                @if(isset($related_product) && $related_product->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-xs space-y-4">
                        <h3 class="font-heading font-bold text-sm text-slate-900 border-b border-slate-100 pb-3 flex items-center">
                            <i class="fa-solid fa-clock text-gold-600 mr-2"></i>
                            <span>Sản phẩm tương tự</span>
                        </h3>
                        <div class="space-y-4">
                            @foreach($related_product as $rel)
                                <div class="flex items-center space-x-3 group">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-50 border border-slate-100 flex-shrink-0 flex items-center justify-center p-1">
                                        <a href="{{ route('web.resolve', ['slug' => lang($rel, 'slug')]) }}" class="w-full h-full flex items-center justify-center">
                                            <img src="{{ asset($rel->image_vn) }}" alt="{{ lang($rel, 'name') }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                                        </a>
                                    </div>
                                    <div class="space-y-0.5 min-w-0">
                                        <h4 class="font-bold text-2xs text-slate-900 leading-snug line-clamp-2 hover:text-gold-600 transition-colors">
                                            <a href="{{ route('web.resolve', ['slug' => lang($rel, 'slug')]) }}">
                                                {{ lang($rel, 'name') }}
                                            </a>
                                        </h4>
                                        <span class="text-xs font-black text-slate-900 block">{{ number_format($rel->price, 0, ',', '.') }}đ</span>
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
