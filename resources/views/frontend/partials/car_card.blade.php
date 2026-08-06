@php
    $productImage = $product->image;
    $productName = lang($product, 'name');
    $productSlug = lang($product, 'slug');
    $price = $product->price;
    $priceOld = $product->price_old;
@endphp
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:border-red-600 transition-all duration-300 flex flex-col group h-full">
    <!-- Clickable Image Container -->
    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
        <a href="{{ route('web.resolve', ['slug' => $productSlug]) }}" class="block w-full h-full">
            @if($productImage)
                <img src="{{ $productImage }}" alt="{{ $productName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400">
                    <i class="fas fa-image text-4xl"></i>
                </div>
            @endif
        </a>
        
        <!-- Hot Badge -->
        @if($product->hot)
            <span class="absolute top-2.5 left-2.5 bg-red-600 text-white text-[10px] font-extrabold uppercase px-2 py-0.5 rounded shadow-md z-10 flex items-center gap-1">
                <i class="fas fa-fire"></i> HOT
            </span>
        @endif

        <!-- Brand Badge -->
        @if($product->brand)
            <span class="absolute top-2.5 right-2.5 bg-slate-900/80 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded z-10">
                {{ $product->brand->name_vn }}
            </span>
        @endif
    </div>

    <!-- Content Info -->
    <div class="p-3 sm:p-4 flex-grow flex flex-col justify-between space-y-3">
        <div>
            <!-- Title -->
            <h3 class="font-bold text-xs sm:text-sm text-slate-900 line-clamp-2 leading-snug group-hover:text-red-600 transition-colors">
                <a href="{{ route('web.resolve', ['slug' => $productSlug]) }}">
                    {{ $productName }}
                </a>
            </h3>

            <!-- Price Row -->
            <div class="mt-2 flex items-baseline gap-2">
                @if($price > 0)
                    <span class="text-xs sm:text-base font-extrabold text-red-600">
                        {{ number_format($price, 0, ',', '.') }} VNĐ
                    </span>
                    @if($priceOld > $price)
                        <span class="text-[10px] sm:text-xs text-slate-400 line-through">
                            {{ number_format($priceOld, 0, ',', '.') }}đ
                        </span>
                    @endif
                @else
                    <span class="text-xs sm:text-base font-extrabold text-red-600">
                        LIÊN HỆ BÁO GIÁ
                    </span>
                @endif
            </div>
        </div>

        <!-- Travel & Hotel Specs Block -->
        <div class="pt-2.5 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-3 gap-x-2 gap-y-1.5 text-[10px] sm:text-[11px] text-slate-600">
            <div class="flex items-center gap-1.5 truncate" title="Lịch trình">
                <i class="fas fa-clock text-red-600 text-xs shrink-0"></i>
                <span class="truncate">Trọn Gói</span>
            </div>
            <div class="flex items-center gap-1.5 truncate" title="Khách sạn / Dịch vụ">
                <i class="fas fa-hotel text-red-600 text-xs shrink-0"></i>
                <span class="truncate">Khách Sạn 5★</span>
            </div>
            <div class="flex items-center gap-1.5 truncate col-span-2 sm:col-span-1" title="Phương tiện">
                <i class="fas fa-plane-departure text-red-600 text-xs shrink-0"></i>
                <span class="truncate">Bay & Ô tô</span>
            </div>
        </div>

        <!-- Action Button -->
        <div class="pt-1">
            <a href="{{ route('web.resolve', ['slug' => $productSlug]) }}" class="w-full bg-slate-900 group-hover:bg-red-600 text-white font-bold text-xs py-2 rounded-lg transition-colors flex items-center justify-center gap-1.5">
                <span>Xem Chi Tiết</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </div>
</div>
