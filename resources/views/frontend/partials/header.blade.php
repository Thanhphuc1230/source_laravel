<nav class="bg-white border-b border-gray-100 sticky top-0 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Brand Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('web.home') }}" class="text-2xl font-heading font-extrabold tracking-wider flex items-center">
                    <span class="text-emerald-950">BASE</span>
                    <span class="text-gold-500 ml-1">TRAVEL</span>
                </a>
            </div>

            <!-- Dynamic Menu Loop -->
            <div class="hidden md:block flex-grow mx-8">
                <ul class="flex space-x-6 justify-center">
                    @if(isset($menu) && $menu->isNotEmpty())
                        @foreach($menu as $item)
                            @php
                                $url = getUrlMenu($item);
                                $isActive = isActiveMenu($item);
                                $activeClass = $isActive 
                                    ? 'text-emerald-900 border-b-2 border-emerald-900 font-extrabold' 
                                    : 'text-gray-600 hover:text-emerald-900 hover:border-b-2 hover:border-emerald-600';
                            @endphp
                            <li>
                                <a href="{{ $url }}" class="pb-2 text-sm font-bold uppercase transition-all duration-200 {{ $activeClass }}">
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
                <a href="{{ route('web.cart') }}" class="relative p-2 text-gray-700 hover:text-emerald-900 transition-colors duration-200 group">
                    <div class="bg-gray-100 hover:bg-gray-250 p-2.5 rounded-full relative">
                        <i class="fa-solid fa-cart-shopping text-lg"></i>
                        <span id="cart-badge-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-3xs font-extrabold rounded-full h-5 w-5 flex items-center justify-center border-2 border-white shadow-sm transition-transform duration-300 {{ ($cart_count ?? 0) > 0 ? 'scale-100' : 'scale-0' }}">
                            {{ $cart_count ?? 0 }}
                        </span>
                    </div>
                </a>

                <!-- Language Switcher -->
                <div class="relative group">
                    <button class="flex items-center space-x-1 text-sm font-semibold text-gray-700 hover:text-emerald-900 focus:outline-none">
                        @if(session('locale') == 'en')
                            <img src="https://flagcdn.com/w20/us.png" alt="English" class="w-5 h-3.5 rounded-sm object-cover">
                            <span>EN</span>
                        @else
                            <img src="https://flagcdn.com/w20/vn.png" alt="Tiếng Việt" class="w-5 h-3.5 rounded-sm object-cover">
                            <span>VI</span>
                        @endif
                        <i class="fa-solid fa-chevron-down text-2xs"></i>
                    </button>
                    <!-- Dropdown -->
                    <div class="absolute right-0 mt-2 w-32 bg-white rounded-lg shadow-lg border border-gray-100 py-1 hidden group-hover:block transition-all duration-200">
                        <a href="{{ route('lang', ['locale' => 'vn']) }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-750 hover:bg-gray-50">
                            <img src="https://flagcdn.com/w20/vn.png" alt="Tiếng Việt" class="w-5 h-3.5 rounded-sm object-cover">
                            <span>Tiếng Việt</span>
                        </a>
                        <a href="{{ route('lang', ['locale' => 'en']) }}" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-750 hover:bg-gray-50">
                            <img src="https://flagcdn.com/w20/us.png" alt="English" class="w-5 h-3.5 rounded-sm object-cover">
                            <span>English</span>
                        </a>
                    </div>
                </div>

                <!-- Hotline Call Button -->
                <div class="hidden lg:block">
                    <a href="tel:{{ $web->phone ?? '18006700' }}" class="flex items-center space-x-2 bg-emerald-950 text-white hover:bg-emerald-900 font-bold px-4 py-2.5 rounded-full border border-emerald-800 transition-colors duration-200 shadow-sm">
                        <i class="fa-solid fa-phone text-xs text-gold-500 animate-pulse"></i>
                        <span class="text-xs uppercase tracking-wide">Hotline: {{ $web->phone ?? '1800 6700' }}</span>
                    </a>
                </div>

                <!-- Hamburger Mobile Menu -->
                <button type="button" onclick="toggleMobileMenu()" class="md:hidden p-2 text-gray-600 hover:text-emerald-950">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 py-3 px-4 shadow-inner">
        <ul class="space-y-3">
            @if(isset($menu) && $menu->isNotEmpty())
                @foreach($menu as $item)
                    @php
                        $url = getUrlMenu($item);
                        $isActive = isActiveMenu($item);
                    @endphp
                    <li>
                        <a href="{{ $url }}" class="block py-2 text-sm font-bold uppercase {{ $isActive ? 'text-emerald-900' : 'text-gray-600' }}">
                            {{ lang($item, 'name') }}
                        </a>
                    </li>
                @endforeach
            @endif
            <li class="pt-2 border-t border-gray-100">
                <a href="tel:{{ $web->phone ?? '18006700' }}" class="flex items-center space-x-2 text-emerald-950 font-bold py-2">
                    <i class="fa-solid fa-phone text-gold-500"></i>
                    <span>Hotline: {{ $web->phone ?? '1800 6700' }}</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<script src="{{ asset('js/header.js') }}"></script>
