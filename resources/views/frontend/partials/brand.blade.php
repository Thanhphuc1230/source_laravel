@if(isset($brands) && $brands->isNotEmpty())
    <section class="max-w-7xl mx-auto px-4 py-10">
        <!-- Title: Just "Thương Hiệu" -->
        <div class="text-center max-w-2xl mx-auto mb-8">
            <h2 class="text-2xl md:text-3xl font-extrabold text-red-900 tracking-tight uppercase">
                Thương hiệu
            </h2>
        </div>

        <!-- Single Row Flex Layout for all Logos -->
        <div class="flex items-center justify-center gap-4 sm:gap-6 lg:gap-8 flex-nowrap overflow-x-auto scrollbar-none py-2">
            @foreach($brands as $brand)
                <a href="{{ route('web.resolve', ['slug' => $brand->slug]) }}" 
                   class="group flex flex-col items-center justify-center shrink-0 p-1.5 transition-all duration-300">
                    <!-- Clean Logo Image -->
                    <div class="h-10 sm:h-12 flex items-center justify-center mb-1 overflow-hidden">
                        @if($brand->image)
                            <img src="{{ $brand->image }}" alt="{{ $brand->name_vn }}" class="h-10 sm:h-12 w-auto object-contain group-hover:scale-110 transition-transform duration-300">
                        @else
                            <i class="fas fa-building text-3xl text-slate-400 group-hover:text-red-700 transition-colors"></i>
                        @endif
                    </div>
                    <!-- Brand Name Below Logo -->
                    <span class="text-xs font-bold text-slate-800 group-hover:text-red-700 transition-colors text-center whitespace-nowrap">
                        {{ $brand->name_vn }}
                    </span>
                </a>
            @endforeach

            <!-- "Xem Thêm" Circular Button on same row -->
            <a href="{{ route('web.home') }}#tours-section" 
               class="group flex flex-col items-center justify-center shrink-0 p-1.5 transition-all duration-300">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-100 group-hover:bg-red-700 flex items-center justify-center text-[10px] font-bold text-red-900 group-hover:text-white transition-all duration-300 shadow-sm border border-slate-200 text-center leading-tight">
                    <span>Xem<br>Thêm</span>
                </div>
                <span class="text-xs font-bold text-transparent select-none">.</span>
            </a>
        </div>
    </section>
@endif
