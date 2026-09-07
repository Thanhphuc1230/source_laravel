@extends('frontend.master')
@section('module', $web->meta_name ?? $web->name_vn)
@section('keywords', $web->meta_keyword)
@section('description', $web->meta_description)
@section('images', $web->favicon)

@section('content')
    <!-- Main Slider Carousel -->
    @include('frontend.partials.slider')

    <!-- Featured Watch Brands Section -->
    @include('frontend.partials.brand')

    <!-- Hot Luxury Watches / Best Sellers Section -->
    <section class="py-16 bg-[#FAFAFA]" id="products-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between mb-12 scroll-anim fade-up">
                <div class="text-center md:text-left">
                    <span class="text-gold-600 text-xs font-extrabold uppercase tracking-widest flex items-center justify-center md:justify-start">
                        <i class="fa-solid fa-crown mr-1.5 text-gold-500"></i> Kiệt Tác Thời Gian
                    </span>
                    <h2 class="text-2xl md:text-3xl font-heading font-extrabold text-slate-900 mt-1 uppercase tracking-wide">
                        Tuyệt Tác Bán Chạy Nhất
                    </h2>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('web.resolve', ['slug' => 'san-pham']) }}" class="inline-flex items-center space-x-2 text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-gold-600 transition-colors group">
                        <span>Khám phá tất cả</span>
                        <i class="fa-solid fa-arrow-right-long text-gold-500 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Products Grid Layout (Mobile: 2 cột theo Rule 5, Desktop: 4 cột) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                @if(isset($hot_products) && $hot_products->isNotEmpty())
                    @foreach($hot_products as $product)
                        <div class="w-full">
                            @include('frontend.components.product-card', ['product' => $product])
                        </div>
                    @endforeach
                @else
                    <div class="col-span-2 md:col-span-4 text-center py-12 text-slate-400">
                        Đang cập nhật bộ sưu tập đồng hồ nổi bật.
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Categories Product List (Đồng hồ nam, Đồng hồ nữ, Automatic, Limited) -->
    @if(isset($category_product) && $category_product->isNotEmpty())
        @foreach($category_product as $cate)
            <section class="py-16 {{ $loop->even ? 'bg-[#FAFAFA]' : 'bg-white' }} border-t border-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-10 pb-4 border-b border-slate-100 scroll-anim fade-up">
                        <div>
                            <span class="text-gold-600 text-xs font-extrabold uppercase tracking-widest">Bộ Sưu Tập</span>
                            <h2 class="text-2xl font-heading font-extrabold text-slate-900 mt-0.5 uppercase tracking-wide">
                                {{ lang($cate, 'name') }}
                            </h2>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <a href="{{ route('web.resolve', ['slug' => $cate->slug]) }}" class="inline-flex items-center space-x-2 text-xs font-bold uppercase tracking-wider text-slate-700 hover:text-gold-600 transition-colors group">
                                <span>Xem bộ sưu tập</span>
                                <i class="fa-solid fa-arrow-right-long text-gold-500 group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                        @if(isset($cate->products) && $cate->products->isNotEmpty())
                            @foreach($cate->products as $product)
                                <div class="w-full">
                                    @include('frontend.components.product-card', ['product' => $product])
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-2 md:col-span-4 text-center py-12 text-slate-400">
                                Đang cập nhật sản phẩm trong danh mục này.
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endforeach
    @endif

    <!-- About / Heritage Section (Light Luxury) -->
    @if(isset($about_section) && $about_section)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-anim fade-up">
            <div class="bg-white border border-slate-100 rounded-3xl p-8 md:p-12 shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-6 space-y-4 text-center lg:text-left">
                        <span class="text-gold-600 text-xs font-extrabold uppercase tracking-widest">Di Sản & Uy Tín</span>
                        <h2 class="text-2xl md:text-3xl font-heading font-extrabold text-slate-900 leading-tight">
                            {{ lang($about_section, 'name') }}
                        </h2>
                        <div class="text-xs md:text-sm text-slate-600 leading-relaxed">
                            {!! lang($about_section, 'content') !!}
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="lg:col-span-6 grid grid-cols-2 gap-4">
                        @if(!empty($about_section->stats) && is_array($about_section->stats))
                            @foreach($about_section->stats as $stat)
                                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 text-center space-y-2 hover:border-gold-500/40 hover:bg-white hover:shadow-md transition-all duration-300">
                                    <div class="w-10 h-10 rounded-full bg-amber-50 text-gold-600 flex items-center justify-center mx-auto text-base">
                                        <i class="{{ $stat['icon'] ?? 'fa-solid fa-gem' }}"></i>
                                    </div>
                                    <div class="text-2xl font-heading font-extrabold text-slate-900">
                                        {{ $stat['value'] ?? '' }}
                                    </div>
                                    <div class="text-3xs font-extrabold text-slate-500 uppercase tracking-wider">
                                        {{ app()->getLocale() === 'en' ? ($stat['name_en'] ?? $stat['name_vn'] ?? '') : ($stat['name_vn'] ?? '') }}
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Features Section (Cam kết giá trị vàng) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-anim fade-up">
        <div class="text-center max-w-xl mx-auto mb-12">
            <span class="text-gold-600 text-xs font-extrabold uppercase tracking-widest">Đặc Quyền Khách Hàng</span>
            <h2 class="text-2xl md:text-3xl font-heading font-extrabold text-slate-900 mt-1 uppercase tracking-wide">
                Cam Kết Giá Trị Vàng
            </h2>
            <div class="gold-divider"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @if(isset($features) && $features->isNotEmpty())
                @foreach($features as $feat)
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 text-center space-y-4 hover:border-gold-500/60 hover:-translate-y-1.5 transition-all duration-300 shadow-xs hover:shadow-xl group">
                        <div class="w-14 h-14 bg-amber-50/80 border border-amber-100 text-gold-600 group-hover:text-white group-hover:bg-gold-500 rounded-2xl flex items-center justify-center mx-auto text-xl font-bold transition-all duration-300 shadow-xs">
                            <i class="{{ $feat->image ?? 'fa-solid fa-gem' }}"></i>
                        </div>
                        <h3 class="font-heading font-bold text-sm text-slate-900 uppercase tracking-wide group-hover:text-gold-600 transition-colors">
                            {{ lang($feat, 'title') }}
                        </h3>
                        <p class="text-3xs text-slate-500 leading-relaxed">
                            {{ lang($feat, 'content') }}
                        </p>
                    </div>
                @endforeach
            @endif
        </div>
    </section>

    <!-- Latest News / Horology Journal Section -->
    <section class="bg-white py-16 border-t border-slate-100 scroll-anim fade-up">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-12">
                <span class="text-gold-600 text-xs font-extrabold uppercase tracking-widest">Tạp Chí Thời Gian</span>
                <h2 class="text-2xl md:text-3xl font-heading font-extrabold text-slate-900 mt-1 uppercase tracking-wide">
                    Tin Tức & Nghệ Thuật Chế Tác
                </h2>
                <div class="gold-divider"></div>
            </div>

            <!-- Mobile: 2 cột theo Rule 5 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if(isset($latest_news) && $latest_news->isNotEmpty())
                    @foreach($latest_news as $post)
                        @php
                            $postName = lang($post, 'name');
                            $postSlug = lang($post, 'slug');
                            $postImage = $post->image_vn;
                            $postDate = $post->created_at ? $post->created_at->format('d/m/Y') : date('d/m/Y');
                        @endphp
                        <article class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col justify-between group h-full">
                            <div class="relative aspect-video overflow-hidden bg-slate-50 zoom-container">
                                <a href="{{ route('web.resolve', ['slug' => $postSlug]) }}" class="block w-full h-full">
                                    <img src="{{ asset($postImage) }}" alt="{{ $postName }}" class="w-full h-full object-cover">
                                </a>
                                <span class="absolute bottom-3 left-3 bg-white/95 border border-slate-100 text-slate-800 text-3xs font-bold px-2.5 py-1 rounded-md tracking-wider shadow-xs backdrop-blur-sm">
                                    {{ $postDate }}
                                </span>
                            </div>
                            <div class="p-5 flex-grow flex flex-col justify-between space-y-3">
                                <div class="space-y-2">
                                    <h3 class="font-heading font-bold text-sm text-slate-900 leading-snug group-hover:text-gold-600 transition-colors line-clamp-2">
                                        <a href="{{ route('web.resolve', ['slug' => $postSlug]) }}">
                                            {{ $postName }}
                                        </a>
                                    </h3>
                                    <p class="text-3xs text-slate-500 leading-relaxed line-clamp-2">
                                        {{ strip_tags(lang($post, 'intro')) }}
                                    </p>
                                </div>
                                <div class="pt-3 border-t border-slate-100">
                                    <a href="{{ route('web.resolve', ['slug' => $postSlug]) }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-700 hover:text-gold-600 transition-colors">
                                        <span>Đọc chi tiết</span>
                                        <i class="fa-solid fa-chevron-right text-3xs text-gold-500"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endsection
