<!-- Mobile Menu Sidebar -->
<div x-show="mobileMenuOpen" 
     x-cloak
     class="fixed inset-0 z-50">
    <!-- Overlay -->
    <div @click="mobileMenuOpen = false" 
         x-show="mobileMenuOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50"></div>
    
    <!-- Sidebar -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 w-80 bg-white shadow-xl overflow-y-auto">
        
        <!-- Close Button -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <span class="text-xl font-bold text-teal-700 italic">Nupex</span>
            <button @click="mobileMenuOpen = false" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Menu Items -->
        <nav class="p-6">
            <ul class="space-y-4">
                <li>
                    <a href="/" class="block text-gray-800 hover:text-teal-600 text-lg py-2 transition">Home</a>
                </li>
                <li>
                    <a href="/research-peptides" class="block text-gray-800 hover:text-teal-600 text-lg py-2 transition">Research Peptides</a>
                </li>
                <li>
                    <a href="/research-capsules" class="block text-gray-800 hover:text-teal-600 text-lg py-2 transition">Research Capsules</a>
                </li>
                <li>
                    <a href="/mixers-solvents" class="block text-gray-800 hover:text-teal-600 text-lg py-2 transition">Mixers & Solvents</a>
                </li>
                <li>
                    <a href="/product-information" class="block text-gray-800 hover:text-teal-600 text-lg py-2 transition">Product Information & Labelling</a>
                </li>
                <li>
                    <a href="/about" class="block text-gray-800 hover:text-teal-600 text-lg py-2 transition">About Us</a>
                </li>
                <li>
                    <a href="/contact" class="block text-gray-800 hover:text-teal-600 text-lg py-2 transition">Contact</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
