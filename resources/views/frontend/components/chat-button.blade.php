<!-- Chat Button -->
<div x-data="{ chatOpen: false }" class="fixed bottom-6 right-6 z-40">
    <!-- Chat Button -->
    <button @click="chatOpen = !chatOpen" 
            class="bg-teal-600 hover:bg-teal-700 text-white rounded-full px-6 py-3 shadow-2xl flex items-center gap-2 transition-all duration-300 transform hover:scale-105">
        <i class="fas fa-comment-dots"></i>
        <span class="font-semibold">Chat with us now.</span>
    </button>
    
    <!-- Chat Widget (Optional - shows when clicked) -->
    <div x-show="chatOpen" 
         @click.away="chatOpen = false"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute bottom-16 right-0 w-96 bg-white rounded-lg shadow-2xl border border-gray-200">
        
        <!-- Chat Header -->
        <div class="bg-teal-600 text-white p-4 rounded-t-lg flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-teal-600">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <h3 class="font-semibold">Customer Support</h3>
                    <p class="text-xs text-teal-100">We typically reply instantly</p>
                </div>
            </div>
            <button @click="chatOpen = false" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Chat Body -->
        <div class="p-4 h-64 overflow-y-auto bg-gray-50">
            <div class="space-y-3">
                <div class="flex gap-2">
                    <div class="w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center text-white text-sm flex-shrink-0">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="bg-white rounded-lg p-3 shadow-sm max-w-xs">
                        <p class="text-sm text-gray-800">Hi! How can we help you today?</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chat Input -->
        <div class="p-4 border-t border-gray-200">
            <div class="flex gap-2">
                <input type="text" 
                       placeholder="Type your message..." 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                <button class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>
