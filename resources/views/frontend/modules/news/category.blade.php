@extends('frontend.master')
@section('module', lang($category_detail, 'name'))
@section('keywords', lang($category_detail, 'keyword'))
@section('description', lang($category_detail, 'description'))
@section('images', asset(lang($category_detail, 'image') ?? 'images/logo/' . $web->logo))

@section('content')
    <div class="bg-emerald-950 text-white py-16 text-center space-y-2">
        <span class="text-xs font-bold uppercase tracking-widest" style="color: {{ $news_settings['category_color'] ?? '#b45309' }}">Cẩm nang du lịch</span>
        <h1 class="text-3xl font-heading font-extrabold">{{ lang($category_detail, 'name') }}</h1>
        <p class="text-3xs text-gray-300">Tổng hợp tin tức, bí quyết du lịch hữu ích nhất dành cho bạn</p>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: News Items Grid (col-span-2) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @if($news->isNotEmpty())
                        @foreach($news as $post)
                            @php
                                $postName = lang($post, 'name');
                                $postSlug = lang($post, 'slug');
                                $postImage = $post->image_vn;
                                $postDate = $post->created_at ? $post->created_at->format('d/m/Y') : '';
                            @endphp
                            <article class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-2xs hover:shadow-md transition-all duration-300 flex flex-col justify-between group h-full">
                                <div class="relative h-48 overflow-hidden bg-gray-200">
                                    @if($news_settings['click_image_detail'] ?? true)
                                        <a href="{{ route('web.resolve', ['slug' => $postSlug]) }}" class="block w-full h-full">
                                            <img src="{{ asset($postImage) }}" alt="{{ $postName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </a>
                                    @else
                                        <div class="block w-full h-full">
                                            <img src="{{ asset($postImage) }}" alt="{{ $postName }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <span class="absolute bottom-4 left-4 bg-emerald-950 text-white text-3xs font-bold px-2.5 py-1 rounded-md tracking-wide">
                                        {{ $postDate }}
                                    </span>
                                </div>
                                <div class="p-5 flex-grow flex flex-col justify-between">
                                    <div class="space-y-2">
                                        <h3 class="font-heading font-bold leading-snug group-hover:text-emerald-700 transition-colors line-clamp-2" style="font-size: {{ $news_settings['font_size'] ?? '14px' }}; color: {{ $news_settings['title_color'] ?? '#064e3b' }}">
                                            <a href="{{ route('web.resolve', ['slug' => $postSlug]) }}">
                                                {{ $postName }}
                                            </a>
                                        </h3>
                                        @if($news_settings['show_intro'] ?? true)
                                            <p class="text-3xs text-gray-400 leading-relaxed line-clamp-2">
                                                {{ strip_tags(lang($post, 'intro')) }}
                                            </p>
                                        @endif
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
                        <div class="col-span-2 bg-white p-12 text-center border border-gray-100 rounded-2xl">
                            <p class="text-xs text-gray-400">Không có bài viết nào thuộc danh mục này.</p>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if($news->hasPages())
                    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex justify-center mt-6">
                        {!! $news->links('frontend.partials.pagination') !!}
                    </div>
                @endif
            </div>

            <!-- Right Column: Sidebar (Hot Tours & Category products) -->
            <div class="space-y-6">
                <!-- Related Categories / Hot tours -->
                @if(isset($product_hot) && $product_hot->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-4">
                        <h3 class="font-heading font-bold text-sm text-emerald-950 border-b border-gray-100 pb-3 flex items-center">
                            <i class="fa-solid fa-fire text-red-500 mr-2"></i>
                            <span>Tour nổi bật nhất</span>
                        </h3>
                        <div class="space-y-4">
                            @foreach($product_hot as $tour)
                                <div class="flex items-center space-x-3 group">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        <a href="{{ route('web.resolve', ['slug' => $tour->slug_vn]) }}">
                                            <img src="{{ asset($tour->image_vn) }}" alt="{{ $tour->name_vn }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </a>
                                    </div>
                                    <div class="space-y-0.5 min-w-0">
                                        <h4 class="font-bold text-2xs text-emerald-950 leading-snug line-clamp-2 hover:text-emerald-700 transition-colors">
                                            <a href="{{ route('web.resolve', ['slug' => $tour->slug_vn]) }}">
                                                {{ $tour->name_vn }}
                                            </a>
                                        </h4>
                                        <span class="text-xs font-black text-red-650 block">{{ number_format($tour->price, 0, ',', '.') }}đ</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
