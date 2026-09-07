@php
    $productImage = $product->image;
    $productName = lang($product, 'name');
    $productSlug = lang($product, 'slug');
    $price = $product->price;
    $priceOld = $product->price_old;
@endphp
<div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-xs hover:shadow-2xl transition-all duration-300 flex flex-col group h-full hover:-translate-y-1.5 scroll-anim fade-up">
    <!-- Clickable Product Image (aspect-square & subtle light bg) -->
    <div class="aspect-square w-full overflow-hidden bg-slate-50 relative p-3 flex items-center justify-center">
        <a href="{{ route('web.resolve', ['slug' => $productSlug]) }}" class="block w-full h-full flex items-center justify-center">
            <img src="{{ asset($productImage) }}" alt="{{ $productName }}" class="w-full h-full object-contain rounded-xl transition-transform duration-500 group-hover:scale-105">
        </a>
        
        <!-- Hot / Limited Badge -->
        @if($product->hot)
            <span class="absolute top-3 left-3 bg-gradient-to-r from-amber-500 to-gold-500 text-white text-3xs font-extrabold uppercase px-2.5 py-1 rounded-full shadow-sm z-10 tracking-wider">
                <i class="fa-solid fa-crown mr-1"></i> Hot Luxury
            </span>
        @endif

        <!-- Category Badge -->
        @if($product->cate)
            <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm border border-slate-200/80 text-slate-700 text-3xs font-bold px-2.5 py-1 rounded-full shadow-xs z-10">
                {{ lang($product->cate, 'name') }}
            </span>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-5 flex-grow flex flex-col justify-between space-y-4">
        <div class="space-y-2">
            <!-- Brand / Quality Indicator -->
            <div class="flex items-center justify-between text-2xs">
                <span class="text-gold-600 font-extrabold uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-gem text-3xs mr-1 text-gold-500"></i> Thụy Sĩ / Swiss Made
                </span>
                <div class="flex items-center text-amber-400 text-3xs">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>

            <!-- Product Title -->
            <h3 class="font-heading font-bold text-sm text-slate-900 group-hover:text-gold-600 transition-colors line-clamp-2 leading-snug">
                <a href="{{ route('web.resolve', ['slug' => $productSlug]) }}">
                    {{ $productName }}
                </a>
            </h3>

            <!-- Highlights -->
            <p class="text-3xs text-slate-500 line-clamp-2 leading-relaxed">
                {{ strip_tags(lang($product, 'intro')) }}
            </p>
        </div>

        <!-- Prices and Actions Row -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
            <div class="flex flex-col">
                @if($priceOld > $price)
                    <span class="text-3xs text-slate-400 line-through leading-none">{{ number_format($priceOld, 0, ',', '.') }}đ</span>
                @endif
                <span class="text-base font-extrabold text-slate-950 leading-none mt-1 group-hover:text-gold-600 transition-colors">
                    {{ number_format($price, 0, ',', '.') }}đ
                </span>
            </div>

            <!-- Add to Cart AJAX Action Button -->
            <button type="button" onclick="addToCartAjax('{{ $product->uuid }}', this)" class="inline-flex items-center justify-center bg-slate-50 hover:bg-gold-500 text-slate-700 hover:text-white border border-slate-200 hover:border-gold-500 font-bold p-2.5 rounded-xl transition-all duration-300 shadow-xs group/btn" title="Thêm vào giỏ hàng">
                <i class="fa-solid fa-bag-shopping text-sm group-hover/btn:scale-110 transition-transform"></i>
            </button>
        </div>
    </div>
</div>
