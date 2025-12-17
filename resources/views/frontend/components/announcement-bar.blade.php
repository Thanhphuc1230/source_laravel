<!-- Announcement Bar -->
<div x-data="{ currentSlide: 0, slides: 2 }" x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides }, 5000)" 
     class="bg-gradient-to-r from-teal-900 to-teal-800 text-white py-3 px-4 relative overflow-hidden">
    <div class="container mx-auto">
        <!-- Slide 1 -->
        <div x-show="currentSlide === 0" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform -translate-x-full"
             class="text-center text-xs md:text-sm">
            <p>Christmas & New Year Closure Notice. We shall be closed over Christmas 24th–26th December. Last DPD collection before Christmas: 23rd December.DPD collections will resume on Saturday 27th December. We shall also be closed for New Year 31st December – 1st January.</p>
        </div>
        
        <!-- Slide 2 -->
        <div x-show="currentSlide === 1" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform -translate-x-full"
             class="text-center text-xs md:text-sm">
            <p>Free UK Shipping on Orders Over £50 | Expert Support Available</p>
        </div>
    </div>
    
    <!-- Navigation Arrows -->
    <button @click="currentSlide = (currentSlide - 1 + slides) % slides" 
            class="absolute left-2 top-1/2 -translate-y-1/2 text-white hover:text-teal-200 transition">
        <i class="fas fa-chevron-left text-sm"></i>
    </button>
    <button @click="currentSlide = (currentSlide + 1) % slides" 
            class="absolute right-2 top-1/2 -translate-y-1/2 text-white hover:text-teal-200 transition">
        <i class="fas fa-chevron-right text-sm"></i>
    </button>
</div>
