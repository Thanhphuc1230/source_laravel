@extends('frontend.master')

@section('title', 'Nupex - Premium Research Peptides')

@section('content')
    <!-- Hero Section -->
    @include('frontend.components.hero')
    
    <!-- Trade Partner CTA Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="grid lg:grid-cols-2 gap-0">
                    <!-- Left: Content -->
                    <div class="p-8 lg:p-12 flex flex-col justify-center">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            {{ $tradePartner['trade_partner_title']->value ?? 'Become a Trade Partner' }}
                        </h2>
                        <p class="text-lg text-gray-600 mb-6">
                            {{ $tradePartner['trade_partner_subtitle']->value ?? '' }}
                        </p>
                        
                        <!-- Features List -->
                        <div class="space-y-4 mb-8">
                            @if(isset($tradePartner['trade_partner_features']->value) && is_array($tradePartner['trade_partner_features']->value))
                                @foreach($tradePartner['trade_partner_features']->value as $feature)
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-5 h-5 rounded-full bg-teal-100 flex items-center justify-center mt-0.5">
                                        <i class="fas fa-check text-teal-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $feature['title'] ?? '' }}</h4>
                                        <p class="text-sm text-gray-600">{{ $feature['description'] ?? '' }}</p>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-wrap items-center gap-4">
                            <a href="{{ $tradePartner['trade_partner_button_link']->value ?? '#' }}" 
                               class="bg-gray-900 hover:bg-gray-800 text-white font-semibold px-6 py-3 rounded-lg transition">
                                {{ $tradePartner['trade_partner_button_text']->value ?? 'Join Today' }}
                            </a>
                            <span class="text-gray-600">{{ $tradePartner['trade_partner_phone_label']->value ?? 'Or call us:' }}</span>
                            <a href="tel:{{ str_replace(' ', '', $tradePartner['trade_partner_phone_number']->value ?? '') }}" 
                               class="text-gray-900 hover:text-teal-600 font-semibold text-lg transition underline">
                                {{ $tradePartner['trade_partner_phone_number']->value ?? '' }}
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right: Image -->
                    <div class="relative h-64 lg:h-auto">
                        @if(isset($tradePartner['trade_partner_image']->value) && $tradePartner['trade_partner_image']->value)
                            <img src="{{ asset('images/site_setting/' . $tradePartner['trade_partner_image']->value) }}" 
                                 alt="Trade Partner" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No image</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Safety Disclaimer -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-gray-900 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Safety Disclaimer!</h3>
                        <p class="text-gray-700">
                            Our peptides are exclusively intended for research purposes and are not
                            authorised for use in humans or animals.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
