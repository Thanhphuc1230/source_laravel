@extends('frontend.master')

@section('title', 'Nupex - Premium Research Peptides')

@section('content')
    <!-- Hero Section -->
    @include('frontend.components.hero')
    
    <!-- Third Party Lab Tested Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Third-Party Lab Tested
                </h2>
                <p class="text-lg text-gray-600">
                    Optimised for Precision. Built for Research Integrity.
                </p>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                        HPLC-Tested for Verified Purity
                    </h3>
                    <p class="text-lg text-gray-600 mb-6">
                        100% verified purity with lab reports
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        Every peptide is tested using HPLC and Mass Spectrometry to confirm purity and
                        potency. We provide lab reports for every batch, and cover third-party testing
                        costs upon request—so you can purchase with complete confidence.
                    </p>
                </div>
                <div class="order-1 lg:order-2">
                    <img src="https://assets.replocdn.com/projects/7b81e9b9-4959-4991-9fc2-a1bd8d58d5b0/df7a0809-b4ec-4624-bc89-cf93a50c6c7c?width=1024" 
                         alt="Lab Testing" 
                         class="w-full rounded-lg shadow-xl">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Quality Features Grid -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: Manufactured -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-industry text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        Manufactured for research consistency
                    </h3>
                    <p class="text-gray-600">
                        Strict quality protocols for research-grade quality
                    </p>
                </div>
                
                <!-- Feature 2: Ethical -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        Ethical & Transparent
                    </h3>
                    <p class="text-gray-600">
                        Science-led. No inflated claims
                    </p>
                </div>
                
                <!-- Feature 3: Next Day Delivery -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-truck-fast text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        Next Day Delivery (UK)
                    </h3>
                    <p class="text-gray-600">
                        Same-day dispatch
                    </p>
                </div>
                
                <!-- Feature 4: International Shipping -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-globe text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        Reliable International Shipping
                    </h3>
                    <p class="text-gray-600">
                        100% verified purity with lab reports
                    </p>
                </div>
                
                <!-- Feature 5: Customer Support -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        Expert Customer Support & Education
                    </h3>
                    <p class="text-gray-600">
                        Fast, knowledgeable UK-based support
                    </p>
                </div>
                
                <!-- Feature 6: Trade Partner -->
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-handshake text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">
                        Become a Trade Partner
                    </h3>
                    <p class="text-gray-600">
                        Unlock exclusive benefits
                    </p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Trade Partner CTA Section -->
    <section class="py-16 bg-gradient-to-br from-teal-800 to-teal-900 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    Become a Trade Partner
                </h2>
                <p class="text-xl mb-8">
                    Unlock exclusive benefits with a Nupex Trade Account:
                </p>
                
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-tag text-teal-300 text-2xl flex-shrink-0"></i>
                        <div class="text-left">
                            <h4 class="font-semibold mb-1">Bulk Discounts</h4>
                            <p class="text-sm text-gray-200">Competitive pricing for high-volume orders</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <i class="fas fa-bolt text-teal-300 text-2xl flex-shrink-0"></i>
                        <div class="text-left">
                            <h4 class="font-semibold mb-1">Priority Order Processing</h4>
                            <p class="text-sm text-gray-200">Fast dispatch to keep your research on track</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <i class="fas fa-user-tie text-teal-300 text-2xl flex-shrink-0"></i>
                        <div class="text-left">
                            <h4 class="font-semibold mb-1">Dedicated Support</h4>
                            <p class="text-sm text-gray-200">Personalised service for your business needs</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="/trade-partner" 
                       class="bg-white text-teal-800 hover:bg-gray-100 font-semibold px-8 py-3 rounded-lg transition">
                        Join Today
                    </a>
                    <span class="text-gray-200">Or call us:</span>
                    <a href="tel:01202155688" 
                       class="text-white hover:text-teal-300 font-semibold text-lg transition">
                        01202 155688
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Safety Disclaimer -->
    <section class="py-12 bg-yellow-50 border-y border-yellow-200">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-yellow-900 text-xl"></i>
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
    </section>
    
    <!-- Contact CTA -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Speak to our team
            </h2>
            <p class="text-xl text-gray-600 mb-8">
                Still have questions?
            </p>
            <a href="/contact" 
               class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold px-8 py-4 rounded-lg text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                Contact us
            </a>
        </div>
    </section>
    
@endsection
