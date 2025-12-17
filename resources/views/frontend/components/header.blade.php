<!-- Header -->
<header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Menu Button (hiển thị cả mobile và desktop) -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-teal-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <!-- Logo -->
            <a href="/" class="flex items-center justify-center flex-1 lg:flex-initial">
                <span class="text-3xl md:text-4xl font-bold text-teal-700 italic">Nupex</span>
            </a>
            
            <!-- Right Icons -->
            <div class="flex items-center gap-4">
                <!-- Country/Currency Selector -->
                <div x-data="{ open: false }" class="relative hidden md:block">
                    <button @click="open = !open" class="text-sm text-gray-700 hover:text-teal-600 flex items-center gap-1">
                        Vietnam | GBP £
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" 
                         @click.away="open = false"
                         x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">United Kingdom | GBP £</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">United States | USD $</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Vietnam | GBP £</a>
                    </div>
                </div>
                
                <!-- User Icon -->
                <a href="#" class="text-gray-700 hover:text-teal-600">
                    <i class="fas fa-user text-lg"></i>
                </a>
                
                <!-- Cart Icon -->
                <a href="#" class="text-gray-700 hover:text-teal-600 relative">
                    <i class="fas fa-shopping-cart text-lg"></i>
                    <span class="absolute -top-2 -right-2 bg-teal-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                </a>
            </div>
        </div>
        
        <!-- Search Bar -->
        <div class="mt-4 max-w-2xl mx-auto">
            <div class="relative">
                <input type="text" 
                       placeholder="Search" 
                       class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-teal-600">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Desktop Navigation Menu -->
    <nav class="hidden md:block border-t border-gray-100 bg-gray-50">
        <div class="container mx-auto px-4">
            <ul class="flex items-center justify-center gap-8 py-3">
                <li>
                    <a href="/" class="text-gray-700 hover:text-teal-600 font-medium transition">Home</a>
                </li>
                <li>
                    <a href="/research-peptides" class="text-gray-700 hover:text-teal-600 font-medium transition">Research Peptides</a>
                </li>
                <li>
                    <a href="/research-capsules" class="text-gray-700 hover:text-teal-600 font-medium transition">Research Capsules</a>
                </li>
                <li>
                    <a href="/mixers-solvents" class="text-gray-700 hover:text-teal-600 font-medium transition">Mixers & Solvents</a>
                </li>
                <li>
                    <a href="/product-information" class="text-gray-700 hover:text-teal-600 font-medium transition">Product Information & Labelling</a>
                </li>
                <li>
                    <a href="/about" class="text-gray-700 hover:text-teal-600 font-medium transition">About Us</a>
                </li>
                <li>
                    <a href="/contact" class="text-gray-700 hover:text-teal-600 font-medium transition">Contact</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
