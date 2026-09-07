<nav class="bg-white/95 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40 shadow-xs transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('web.home') }}" class="flex items-center space-x-2 group">
                    @if(!empty($web->logo))
                        <img src="{{ asset($web->logo) }}" alt="{{ $web->name_vn ?? 'Luxury Watches' }}" class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                    @else
                        <span class="text-xl md:text-2xl font-heading font-extrabold tracking-widest uppercase text-slate-900">
                            {{ $web->name_vn ?? 'AURA WATCHES' }}
                        </span>
                    @endif
                </a>
            </div>

            <!-- Dynamic Menu Loop -->
            <div class="hidden md:block flex-grow mx-8">
                <ul class="flex space-x-8 justify-center items-center">
                    @if(isset($menu) && $menu->isNotEmpty())
                        @foreach($menu as $item)
                            @php
                                $url = getUrlMenu($item);
                                $isActive = isActiveMenu($item);
                            @endphp
                            <li>
                                <a href="{{ $url }}" class="nav-link-luxury {{ $isActive ? 'active text-gold-600' : '' }}">
                                    {{ lang($item, 'name') }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <!-- Header Actions (Cart, Hotline, Lang) -->
            <div class="flex items-center space-x-4">
                <!-- Cart Icon with Badge -->
                <a href="{{ route('web.cart') }}" class="relative p-2 text-slate-700 hover:text-gold-600 transition-colors duration-200 group" title="Giỏ hàng">
                    <div class="bg-slate-50 hover:bg-slate-100 p-2.5 rounded-full border border-slate-200 hover:border-gold-500/50 transition-all duration-300 relative shadow-2xs">
                        <i class="fa-solid fa-bag-shopping text-base text-slate-800 group-hover:text-gold-600 transition-colors"></i>
                        <span id="cart-badge-count" class="absolute -top-1 -right-1 bg-gold-500 text-white text-3xs font-extrabold rounded-full h-5 w-5 flex items-center justify-center border-2 border-white shadow-xs transition-transform duration-300 {{ ($cart_count ?? 0) > 0 ? 'scale-100' : 'scale-0' }}">
                            {{ $cart_count ?? 0 }}
                        </span>
                    </div>
                </a>

                <!-- Language Switcher -->
                <div class="relative group">
                    <button class="flex items-center space-x-1.5 text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-gold-600 bg-slate-50 border border-slate-200 px-2.5 py-1.5 rounded-full focus:outline-none transition-all duration-200">
                        @if(session('locale') == 'en')
                            <img src="https://flagcdn.com/w20/us.png" alt="English" class="w-4 h-3 rounded-sm object-cover">
                            <span>EN</span>
                        @else
                            <img src="https://flagcdn.com/w20/vn.png" alt="Tiếng Việt" class="w-4 h-3 rounded-sm object-cover">
                            <span>VI</span>
                        @endif
                        <i class="fa-solid fa-chevron-down text-3xs text-slate-400"></i>
                    </button>
                    <!-- Dropdown -->
                    <div class="absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 hidden group-hover:block transition-all duration-200 z-50">
                        <a href="{{ route('lang', ['locale' => 'vn']) }}" class="flex items-center space-x-2.5 px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-gold-600 transition-colors">
                            <img src="https://flagcdn.com/w20/vn.png" alt="Tiếng Việt" class="w-4 h-3 rounded-sm object-cover">
                            <span>Tiếng Việt</span>
                        </a>
                        <a href="{{ route('lang', ['locale' => 'en']) }}" class="flex items-center space-x-2.5 px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-gold-600 transition-colors">
                            <img src="https://flagcdn.com/w20/us.png" alt="English" class="w-4 h-3 rounded-sm object-cover">
                            <span>English</span>
                        </a>
                    </div>
                </div>

                <!-- Hotline Call Button -->
                @if(!empty($web->phone))
                <div class="hidden lg:block">
                    <a href="tel:{{ $web->phone }}" class="flex items-center space-x-2 bg-slate-900 hover:bg-gold-500 text-white hover:text-white font-bold px-4 py-2 rounded-full border border-slate-800 hover:border-gold-500 transition-all duration-300 shadow-xs group">
                        <i class="fa-solid fa-phone text-xs text-gold-400 group-hover:text-white animate-pulse"></i>
                        <span class="text-xs uppercase tracking-wider font-semibold">{{ $web->phone }}</span>
                    </a>
                </div>
                @endif

                <!-- Hamburger Mobile Menu -->
                <button type="button" onclick="toggleMobileMenu()" class="md:hidden p-2 text-slate-700 hover:text-gold-600 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-slate-100 py-4 px-6 shadow-xl">
        <ul class="space-y-3">
            @if(isset($menu) && $menu->isNotEmpty())
                @foreach($menu as $item)
                    @php
                        $url = getUrlMenu($item);
                        $isActive = isActiveMenu($item);
                    @endphp
                    <li>
                        <a href="{{ $url }}" class="block py-2 text-sm font-bold uppercase tracking-wider {{ $isActive ? 'text-gold-600' : 'text-slate-700 hover:text-gold-600' }}">
                            {{ lang($item, 'name') }}
                        </a>
                    </li>
                @endforeach
            @endif
            @if(!empty($web->phone))
            <li class="pt-3 border-t border-slate-100">
                <a href="tel:{{ $web->phone }}" class="flex items-center space-x-2 text-gold-600 font-bold py-2">
                    <i class="fa-solid fa-phone text-gold-500"></i>
                    <span class="text-xs uppercase tracking-wider">Hotline: {{ $web->phone }}</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</nav>

<script src="{{ asset('js/header.js') }}"></script>
