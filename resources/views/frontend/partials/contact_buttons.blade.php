<!-- Floating Contact Buttons (Luxury Dark & Gold) -->
<div class="fixed right-5 bottom-8 z-50 flex flex-col space-y-3">
    @if(!empty($web->phone))
    <!-- Hotline Call Button -->
    <a href="tel:{{ $web->phone }}" class="group relative flex items-center justify-center w-12 h-12 md:w-13 md:h-13 rounded-full bg-gold-gradient text-dark-950 shadow-2xl hover:scale-110 transition-all duration-300 gold-border-glow">
        <span class="absolute inset-0 rounded-full bg-gold-400/40 animate-ping"></span>
        <i class="fa-solid fa-phone text-base md:text-lg"></i>
        <span class="absolute right-15 bg-dark-850 border border-gold-500/40 text-gold-400 text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-2xl whitespace-nowrap pointer-events-none">
            Hotline: {{ $web->phone }}
        </span>
    </a>
    @endif

    @if(!empty($web->zalo))
    <!-- Zalo Chat Button -->
    <a href="{{ $web->zalo }}" target="_blank" class="group relative flex items-center justify-center w-12 h-12 md:w-13 md:h-13 rounded-full bg-dark-850 border border-gold-500/40 text-gold-400 shadow-2xl hover:border-gold-500 hover:bg-dark-700 hover:scale-110 transition-all duration-300">
        <i class="fa-solid fa-comments text-base md:text-lg"></i>
        <span class="absolute right-15 bg-dark-850 border border-gold-500/40 text-gold-400 text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-2xl whitespace-nowrap pointer-events-none">
            Tư vấn VIP Zalo
        </span>
    </a>
    @endif

    @if(!empty($web->facebook))
    <!-- Facebook Messenger Button -->
    <a href="{{ $web->facebook }}" target="_blank" class="group relative flex items-center justify-center w-12 h-12 md:w-13 md:h-13 rounded-full bg-dark-850 border border-gold-500/40 text-gold-400 shadow-2xl hover:border-gold-500 hover:bg-dark-700 hover:scale-110 transition-all duration-300">
        <i class="fa-brands fa-facebook-messenger text-base md:text-lg"></i>
        <span class="absolute right-15 bg-dark-850 border border-gold-500/40 text-gold-400 text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-2xl whitespace-nowrap pointer-events-none">
            Messenger VIP
        </span>
    </a>
    @endif
</div>
