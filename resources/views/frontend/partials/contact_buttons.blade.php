<!-- Floating Contact Sidebar (Fixed Bottom-Left) -->
<div class="fixed left-4 bottom-10 md:bottom-20 z-50 flex flex-col space-y-4">
    <!-- Hotline Call Button -->
    <a href="tel:{{ $web->phone ?? '18006700' }}" class="group relative flex items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full bg-red-500 text-white shadow-lg hover:scale-110 transition-transform duration-300">
        <span class="absolute inset-0 rounded-full bg-red-500/30 animate-ping"></span>
        <i class="fa-solid fa-phone text-lg md:text-xl"></i>
        <span class="absolute left-16 bg-red-650 text-white text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-md whitespace-nowrap pointer-events-none">
            Gọi Hotline: {{ $web->phone ?? '1800 6700' }}
        </span>
    </a>

    <!-- Zalo Chat Button -->
    <a href="{{ $web->zalo ?? 'https://zalo.me' }}" target="_blank" class="group relative flex items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full bg-[#0084FF] text-white shadow-lg hover:scale-110 transition-transform duration-300">
        <i class="fa-solid fa-comments text-lg md:text-xl"></i>
        <span class="absolute left-16 bg-[#0084FF] text-white text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-md whitespace-nowrap pointer-events-none">
            Chat Zalo tư vấn
        </span>
    </a>

    <!-- Facebook Messenger Button -->
    <a href="{{ $web->facebook ?? 'https://m.me' }}" target="_blank" class="group relative flex items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full bg-[#1877F2] text-white shadow-lg hover:scale-110 transition-transform duration-300">
        <i class="fa-brands fa-facebook-messenger text-lg md:text-xl"></i>
        <span class="absolute left-16 bg-[#1877F2] text-white text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-md whitespace-nowrap pointer-events-none">
            Facebook Fanpage
        </span>
    </a>
</div>
