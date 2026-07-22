@extends('frontend.master')
@section('module', $web->meta_name)
@section('keywords', $web->meta_keyword)
@section('description', $web->meta_description)
@section('images', $web->favicon)

@section('content')
    <!-- Main Slider Carousel -->
    @include('frontend.partials.slider')

    <!-- Search Widget Section -->
    <div class="relative z-20 -mt-14 max-w-7xl mx-auto px-4">
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100">
            <form action="#" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1.5"><i class="fa-solid fa-map-location-dot mr-1"></i> Điểm Đến</label>
                    <div class="relative">
                        <select name="category" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 appearance-none">
                            <option value="0">Tất cả các tour</option>
                            <option value="1">Tour Trong Nước</option>
                            <option value="2">Tour Quốc Tế</option>
                            <option value="3">Tour Cao Cấp</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-2xs text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1.5"><i class="fa-solid fa-money-bill-wave mr-1"></i> Ngân Sách</label>
                    <div class="relative">
                        <select name="price" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 appearance-none">
                            <option value="">Mọi mức giá</option>
                            <option value="under_5">Dưới 5 triệu</option>
                            <option value="5_15">5 triệu - 15 triệu</option>
                            <option value="above_15">Trên 15 triệu</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-2xs text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-3xs font-extrabold text-gray-500 uppercase mb-1.5"><i class="fa-solid fa-magnifying-glass mr-1"></i> Tìm nhanh</label>
                    <input type="text" name="search" placeholder="Nhập tên tour du lịch..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:border-emerald-600 placeholder-gray-400">
                </div>

                <div>
                    <button type="submit" class="w-full bg-gold-500 hover:bg-gold-600 text-emerald-950 font-bold uppercase tracking-wider text-xs py-3.5 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg hover:scale-102 flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Tìm kiếm Tour</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Features Section (Tại sao chọn chúng tôi - Dynamic Values) -->
    <section class="max-w-7xl mx-auto px-4 py-16">
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-gold-650 text-xs font-extrabold uppercase tracking-widest">BASE Value</span>
            <h2 class="text-2xl md:text-3xl font-heading font-extrabold text-emerald-950 mt-1">Tại Sao Chọn Chúng Tôi?</h2>
            <p class="text-3xs text-gray-400 mt-2">Đội ngũ phục vụ chuyên nghiệp, tận tâm mang lại dịch vụ hoàn hảo nhất</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @if(isset($features) && $features->isNotEmpty())
                @foreach($features as $feat)
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 text-center space-y-4 hover:border-emerald-900 hover:shadow-md transition-all duration-300">
                        <div class="w-14 h-14 bg-emerald-50 text-emerald-950 rounded-full flex items-center justify-center mx-auto text-xl font-bold shadow-sm">
                            <i class="{{ $feat->image ?? 'fa-solid fa-star' }} text-emerald-900"></i>
                        </div>
                        <h3 class="font-heading font-bold text-sm text-emerald-950">{{ lang($feat, 'title') }}</h3>
                        <p class="text-3xs text-gray-400 leading-relaxed">{{ lang($feat, 'content') }}</p>
                    </div>
                @endforeach
            @else
                <div class="text-center col-span-4 p-4 text-gray-400">Chưa có dữ liệu giá trị cốt lõi.</div>
            @endif
        </div>
    </section>

    <!-- Hot Tours / Best Sellers Section -->
    <section class="bg-gray-100 py-16" id="tours-section">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between mb-10">
                <div class="text-center md:text-left">
                    <span class="text-red-500 text-xs font-extrabold uppercase tracking-widest"><i class="fa-solid fa-fire mr-1"></i> Hot Sellers</span>
                    <h2 class="text-2xl md:text-3xl font-heading font-extrabold text-emerald-950 mt-1">Tour Du Lịch Bán Chạy Nhất</h2>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="#" class="inline-flex items-center space-x-1.5 text-xs font-bold text-emerald-900 hover:text-gold-600 transition-colors">
                        <span>Tất cả các tour</span>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </a>
                </div>
            </div>

            <!-- Tours Grid Layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @if(isset($hot_products) && $hot_products->isNotEmpty())
                    @foreach($hot_products as $tour)
                        <div class="w-full">
                            @include('frontend.partials.tour_card', ['tour' => $tour])
                        </div>
                    @endforeach
                @else
                    <div class="col-span-4 text-center py-12 text-gray-400">Hiện tại chưa có tour du lịch nổi bật nào.</div>
                @endif
            </div>
        </div>
    </section>

    <!-- Categories Tour List (Trong Nước, Quốc Tế, Cao Cấp) -->
    @if(isset($category_product) && $category_product->isNotEmpty())
        @foreach($category_product as $cate)
            <section class="max-w-7xl mx-auto px-4 py-16">
                <div class="flex flex-col md:flex-row items-center justify-between mb-10 border-b border-gray-100 pb-4">
                    <div>
                        <span class="text-gold-650 text-xs font-extrabold uppercase tracking-widest">Danh mục</span>
                        <h2 class="text-2xl font-heading font-extrabold text-emerald-950 mt-0.5">{{ lang($cate, 'name') }}</h2>
                    </div>
                    <div class="mt-2 md:mt-0">
                        <a href="{{ route('web.resolve', ['slug' => $cate->slug]) }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-emerald-900 hover:text-gold-600 transition-colors">
                            <span>Xem thêm</span>
                            <i class="fa-solid fa-arrow-right-long"></i>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @if(isset($cate->products) && $cate->products->isNotEmpty())
                        @foreach($cate->products as $tour)
                            <div class="w-full">
                                @include('frontend.partials.tour_card', ['tour' => $tour])
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-4 text-center py-12 text-gray-400">Không tìm thấy tour nào thuộc danh mục này.</div>
                    @endif
                </div>
            </section>
        @endforeach
    @endif

    <!-- Latest News Blog Section -->
    <section class="bg-white py-16 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-xl mx-auto mb-10">
                <span class="text-gold-650 text-xs font-extrabold uppercase tracking-widest">Cẩm nang</span>
                <h2 class="text-2xl md:text-3xl font-heading font-extrabold text-emerald-950 mt-1">Tin Tức & Kinh Nghiệm Du Lịch</h2>
                <p class="text-3xs text-gray-400 mt-2">Cập nhật tin tức du lịch, kinh nghiệm tham quan, ẩm thực từ chuyên gia</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @if(isset($latest_news) && $latest_news->isNotEmpty())
                    @foreach($latest_news as $post)
                        @php
                            $postName = lang($post, 'name');
                            $postSlug = lang($post, 'slug');
                            $postImage = $post->image_vn;
                            $postDate = $post->created_at ? $post->created_at->format('d/m/Y') : '14/07/2026';
                        @endphp
                        <article class="bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden shadow-2xs hover:shadow-md transition-all duration-300 flex flex-col justify-between group h-full">
                            <div class="relative h-48 overflow-hidden bg-gray-200">
                                <a href="{{ route('web.resolve', ['slug' => $postSlug]) }}" class="block w-full h-full">
                                    <img src="{{ asset($postImage) }}" alt="{{ $postName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </a>
                                <span class="absolute bottom-4 left-4 bg-emerald-950 text-white text-3xs font-bold px-2.5 py-1 rounded-md tracking-wide">
                                    {{ $postDate }}
                                </span>
                            </div>
                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div class="space-y-2">
                                    <h3 class="font-heading font-bold text-sm text-emerald-950 leading-snug group-hover:text-emerald-700 transition-colors line-clamp-2">
                                        <a href="{{ route('web.resolve', ['slug' => $postSlug]) }}">
                                            {{ $postName }}
                                        </a>
                                    </h3>
                                    <p class="text-3xs text-gray-400 leading-relaxed line-clamp-2">
                                        {{ strip_tags(lang($post, 'intro')) }}
                                    </p>
                                </div>
                                <div class="pt-4">
                                    <a href="{{ route('web.resolve', ['slug' => $postSlug]) }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-emerald-900 group-hover:text-gold-600 transition-colors">
                                        <span>Đọc chi tiết</span>
                                        <i class="fa-solid fa-chevron-right text-3xs"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <div class="col-span-3 text-center py-12 text-gray-400">Hiện tại chưa có tin tức du lịch nào.</div>
                @endif
            </div>
        </div>
    </section>
@endsection
