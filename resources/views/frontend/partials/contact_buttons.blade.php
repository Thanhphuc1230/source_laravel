<!-- Floating Contact Sidebar & Move to Top (Fixed Bottom-Right per Rule 5) -->
<div class="fixed right-5 bottom-5 z-50 flex flex-col space-y-3 items-center">
    <!-- Hotline Call Button -->
    <a href="tel:{{ $web->phone }}" class="group relative flex items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full bg-red-600 text-white shadow-xl hover:scale-110 transition-transform duration-300">
        <span class="absolute inset-0 rounded-full bg-red-600/40 animate-ping"></span>
        <i class="fas fa-phone-alt text-lg md:text-xl"></i>
        <span class="absolute right-16 bg-slate-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-md whitespace-nowrap pointer-events-none">
            Hotline: {{ $web->phone }}
        </span>
    </a>

    <!-- Zalo Chat Button -->
    <a href="{{ $web->zalo ?? 'https://zalo.me' }}" target="_blank" class="group relative flex items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full bg-[#0084FF] text-white shadow-xl hover:scale-110 transition-transform duration-300">
        <i class="fas fa-comment-dots text-lg md:text-xl"></i>
        <span class="absolute right-16 bg-slate-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-md whitespace-nowrap pointer-events-none">
            Chat Zalo tư vấn
        </span>
    </a>

    <!-- Move to Top Button -->
    <button id="moveToTopBtn" type="button" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Lên đầu trang" class="group relative items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full bg-[#0a2540] hover:bg-orange-500 text-white shadow-xl hover:scale-110 transition-all duration-300 cursor-pointer border border-white/20" style="display: none;">
        <i class="fas fa-arrow-up text-lg md:text-xl"></i>
        <span class="absolute right-16 bg-slate-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-md whitespace-nowrap pointer-events-none">
            Lên đầu trang
        </span>
    </button>
</div>

<script>
    (function() {
        function checkScrollPosition() {
            var btn = document.getElementById('moveToTopBtn');
            if (!btn) return;
            if (window.scrollY > 200 || document.documentElement.scrollTop > 200) {
                btn.style.display = 'flex';
            } else {
                btn.style.display = 'none';
            }
        }
        window.addEventListener('scroll', checkScrollPosition, { passive: true });
        window.addEventListener('DOMContentLoaded', checkScrollPosition);
        checkScrollPosition();
    })();
</script>
