@php
    $tourImage = $tour->image;
    $tourName = lang($tour, 'name');
    $tourSlug = lang($tour, 'slug');
    $price = $tour->price;
    $priceOld = $tour->price_old;
@endphp
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col group h-full">
    <!-- Clickable Tour Image -->
    <div class="relative h-56 overflow-hidden bg-gray-150 zoom-effect">
        @if($product_settings['click_image_detail'] ?? true)
            <a href="{{ route('web.resolve', ['slug' => $tourSlug]) }}" class="block w-full h-full">
                <img src="{{ asset($tourImage) }}" alt="{{ $tourName }}" class="w-full h-full object-cover">
            </a>
        @else
            <div class="block w-full h-full">
                <img src="{{ asset($tourImage) }}" alt="{{ $tourName }}" class="w-full h-full object-cover">
            </div>
        @endif
        
        <!-- Hot Badge -->
        @if($tour->hot)
            <span class="absolute top-4 left-4 bg-red-500 text-white text-3xs font-extrabold uppercase px-2.5 py-1 rounded-md tracking-wider shadow-sm z-10">
                <i class="fa-solid fa-fire mr-1"></i> Hot Tour
            </span>
        @endif

        <!-- Category Badge -->
        @if($tour->cate)
            <span class="absolute top-4 right-4 bg-emerald-950/80 backdrop-blur-sm text-3xs font-bold px-2.5 py-1 rounded-md tracking-wide z-10" style="color: {{ $product_settings['category_color'] ?? '#b45309' }}">
                {{ lang($tour->cate, 'name') }}
            </span>
        @endif
    </div>

    <!-- Tour Content Info -->
    <div class="p-5 flex-grow flex flex-col justify-between">
        <div class="space-y-2">
            <!-- Rating Star & Code -->
            <div class="flex items-center justify-between text-2xs">
                <div class="flex items-center text-gold-500">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <span class="text-gray-500 ml-1.5 font-bold text-3xs">5.0</span>
                </div>
                <span class="text-gray-400 font-medium">Mã: HT-{{ $tour->id_product }}</span>
            </div>

            <!-- Tour Title Link -->
            <h3 class="font-heading font-bold line-clamp-2 leading-snug group-hover:text-emerald-700 transition-colors" style="font-size: {{ $product_settings['font_size'] ?? '14px' }}; color: {{ $product_settings['title_color'] ?? '#064e3b' }}">
                <a href="{{ route('web.resolve', ['slug' => $tourSlug]) }}">
                    {{ $tourName }}
                </a>
            </h3>

            <!-- Basic stats icons row -->
            <div class="flex items-center space-x-3 text-3xs text-gray-500 pt-1">
                <span><i class="fa-solid fa-clock mr-1 text-emerald-800"></i> Lịch trình trọn gói</span>
                <span><i class="fa-solid fa-plane-departure mr-1 text-emerald-800"></i> Bay & Ô tô</span>
            </div>
        </div>

        <!-- Prices and Actions Row -->
        <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between">
            <div class="flex flex-col">
                @if($priceOld > $price)
                    <span class="text-3xs text-gray-400 line-through leading-none">{{ number_format($priceOld, 0, ',', '.') }}đ</span>
                @endif
                <span class="text-base font-extrabold text-red-650 leading-none">{{ number_format($price, 0, ',', '.') }}đ</span>
            </div>

            <!-- Add to Cart AJAX Action Button -->
            <button type="button" onclick="addToCartAjax('{{ $tour->uuid }}', this)" class="inline-flex items-center justify-center bg-emerald-950 text-white hover:bg-emerald-900 font-bold p-2.5 rounded-xl transition-all duration-300 hover:scale-105 active:scale-95 group/btn" title="Thêm vào giỏ hàng">
                <i class="fa-solid fa-cart-plus text-sm group-hover/btn:translate-x-0.5 transition-transform"></i>
            </button>
        </div>
    </div>
</div>
