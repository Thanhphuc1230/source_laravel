@if(isset($sliders) && $sliders->isNotEmpty())
<div class="relative h-[280px] sm:h-[400px] md:h-[550px] w-full overflow-hidden bg-emerald-950" id="main-slider">
    <!-- Slides Wrapper -->
    @foreach($sliders as $key => $slide)
        <div class="absolute inset-0 transition-all duration-1000 ease-in-out transform {{ $key === 0 ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-105 z-0' }} slide-item" data-slide-index="{{ $key }}">
            <!-- Tải trực tiếp ảnh từ local -->
            <img src="{{ asset($slide->image) }}" alt="{{ $slide->name_vn }}" class="w-full h-full object-cover opacity-75">
            <!-- Subtle gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-950/70 via-emerald-950/20 to-transparent"></div>
            
            <!-- Elegant textual overlay -->
            <div class="absolute inset-0 flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-xl text-white space-y-3 sm:space-y-4">
                        <span class="inline-block bg-gold-500 text-emerald-950 text-3xs font-extrabold uppercase px-3 py-1 rounded-full tracking-wider animate-bounce">
                            BASE TRAVEL
                        </span>
                        <h2 class="text-xl sm:text-3xl md:text-5xl font-heading font-extrabold leading-tight text-shadow">
                            {{ lang($slide, 'name') }}
                        </h2>
                        <div class="pt-2">
                            <a href="#tours-section" class="inline-flex items-center space-x-2 bg-gold-500 hover:bg-gold-600 text-emerald-950 font-bold text-xs uppercase px-5 py-3 rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
                                <span>Khám phá ngay</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Navigation arrows -->
    <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/30 hover:bg-emerald-950 text-white flex items-center justify-center transition-colors">
        <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/30 hover:bg-emerald-950 text-white flex items-center justify-center transition-colors">
        <i class="fa-solid fa-chevron-right"></i>
    </button>

    <!-- Pagination dots -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex space-x-2.5">
        @foreach($sliders as $key => $slide)
            <button onclick="goToSlide({{ $key }})" class="w-3.5 h-3.5 rounded-full border border-white/30 transition-all duration-300 slide-dot {{ $key === 0 ? 'bg-gold-500 w-8 border-gold-500' : 'bg-white/40' }}" data-slide-dot="{{ $key }}"></button>
        @endforeach
    </div>
</div>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide-item');
    const dots = document.querySelectorAll('.slide-dot');
    const totalSlides = slides.length;
    let slideInterval;

    function showSlide(index) {
        if (index >= totalSlides) index = 0;
        if (index < 0) index = totalSlides - 1;
        
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0', 'scale-105', 'z-0');
                slide.classList.add('opacity-100', 'scale-100', 'z-10');
            } else {
                slide.classList.remove('opacity-100', 'scale-100', 'z-10');
                slide.classList.add('opacity-0', 'scale-105', 'z-0');
            }
        });

        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.add('bg-gold-500', 'w-8', 'border-gold-500');
                dot.classList.remove('bg-white/40');
            } else {
                dot.classList.remove('bg-gold-500', 'w-8', 'border-gold-500');
                dot.classList.add('bg-white/40');
            }
        });

        currentSlide = index;
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
        resetInterval();
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
        resetInterval();
    }

    function goToSlide(index) {
        showSlide(index);
        resetInterval();
    }

    function startInterval() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    function resetInterval() {
        clearInterval(slideInterval);
        startInterval();
    }

    // Initialize carousel autoplay
    document.addEventListener('DOMContentLoaded', () => {
        if (totalSlides > 0) {
            startInterval();
        }
    });
</script>
@endif
