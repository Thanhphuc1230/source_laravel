@if(isset($sliders) && $sliders->isNotEmpty())
<div class="relative h-[320px] sm:h-[450px] md:h-[580px] w-full overflow-hidden bg-slate-900" id="main-slider">
    <!-- Slides Wrapper -->
    @foreach($sliders as $key => $slide)
        <div class="absolute inset-0 transition-all duration-1000 ease-in-out transform {{ $key === 0 ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-105 z-0' }} slide-item" data-slide-index="{{ $key }}">
            <picture class="w-full h-full block">
                <source media="(max-width: 767px)" srcset="{{ asset(lang($slide, 'image_mobile')) }}">
                <img src="{{ asset(lang($slide, 'image_desktop')) }}" alt="{{ lang($slide, 'name') }}" class="w-full h-full object-cover opacity-85">
            </picture>
            <!-- High-contrast elegant gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-900/30 to-transparent"></div>
            
            <!-- Elegant textual overlay -->
            <div class="absolute inset-0 flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-2xl text-white space-y-4">
                        <span class="inline-block bg-white/95 border border-gold-400/60 text-gold-700 text-3xs font-extrabold uppercase px-4 py-1.5 rounded-full tracking-widest shadow-md backdrop-blur-sm">
                            {{ $web->name_vn ?? 'AURA LUXURY WATCHES' }}
                        </span>
                        <h2 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-extrabold leading-tight text-white drop-shadow-md uppercase tracking-wide">
                            {{ lang($slide, 'name') }}
                        </h2>
                        <div class="pt-2">
                            <a href="#products-section" class="btn-gold">
                                <span>Khám Phá Ngay</span>
                                <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Navigation arrows -->
    <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/80 hover:bg-gold-500 text-slate-800 hover:text-white border border-slate-200 shadow-md flex items-center justify-center transition-all duration-300 backdrop-blur-sm">
        <i class="fa-solid fa-chevron-left text-sm"></i>
    </button>
    <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-white/80 hover:bg-gold-500 text-slate-800 hover:text-white border border-slate-200 shadow-md flex items-center justify-center transition-all duration-300 backdrop-blur-sm">
        <i class="fa-solid fa-chevron-right text-sm"></i>
    </button>

    <!-- Pagination dots -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex space-x-2.5">
        @foreach($sliders as $key => $slide)
            <button onclick="goToSlide({{ $key }})" class="w-3.5 h-3.5 rounded-full border border-white/40 transition-all duration-300 slide-dot {{ $key === 0 ? 'bg-gold-500 w-8 border-gold-500' : 'bg-white/50' }}" data-slide-dot="{{ $key }}"></button>
        @endforeach
    </div>
</div>

<script src="{{ asset('js/slider.js') }}"></script>
@endif
