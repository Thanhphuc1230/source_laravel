<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-[#0c4a5e] via-[#0a3d4f] to-[#083544] overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v6h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>
    
    <div class="container mx-auto px-4 py-12 lg:py-20 relative z-10">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center">
            <!-- Left Side - Product Image -->
            @if(isset($homepageSettings['hero_image']))
            <div class="flex justify-center lg:justify-start">
                <div class="relative w-full">
                    @php
                        $heroImageSrc = $homepageSettings['hero_image']->value;
                        // Nếu là tên file (không có http/https), thêm đường dẫn storage
                        if (!str_starts_with($heroImageSrc, 'http')) {
                            $heroImageSrc = asset('images/site_setting/' . $heroImageSrc);
                        }
                    @endphp
                    <img src="{{ $heroImageSrc }}" 
                         alt="Research Peptides" 
                         class="w-full max-w-lg lg:max-w-2xl mx-auto drop-shadow-2xl">
                </div>
            </div>
            @endif
            
            <!-- Right Side - Content -->
            <div class="text-white space-y-6">
                @if(isset($homepageSettings['hero_title']))
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                    {{ $homepageSettings['hero_title']->value }}
                </h1>
                @endif
                
                @if(isset($homepageSettings['hero_description']))
                <p class="text-lg md:text-xl text-gray-200 leading-relaxed">
                    @if(isset($homepageSettings['hero_subtitle']))
                        <span class="font-semibold">{{ $homepageSettings['hero_subtitle']->value }}</span>
                    @endif
                    {{ $homepageSettings['hero_description']->value }}
                </p>
                @endif
                
                @if(isset($homepageSettings['hero_button_text']) && isset($homepageSettings['hero_button_link']))
                <div class="pt-4">
                    <a href="{{ $homepageSettings['hero_button_link']->value }}" 
                       class="inline-block bg-[#0c7a8e] hover:bg-[#0a6476] text-white font-semibold px-8 py-4 rounded-md text-lg transition-all duration-300 transform hover:scale-105 shadow-xl">
                        {{ $homepageSettings['hero_button_text']->value }}
                    </a>
                </div>
                @endif
                
                <!-- Features Grid -->
                @if(isset($homepageSettings['features']))
                    @php
                        $features = $homepageSettings['features']->value;
                        $features = is_string($features) ? json_decode($features, true) : $features;
                    @endphp
                    @if(is_array($features) && count($features) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-8">
                        @foreach($features as $feature)
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/10 rounded-full flex items-center justify-center">
                                <i class="{{ $feature['icon'] ?? 'fas fa-check' }} text-cyan-300"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-sm">{{ $feature['title'] ?? '' }}</h3>
                                @if(!empty($feature['subtitle']))
                                <p class="text-xs text-gray-300">{{ $feature['subtitle'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</section>
