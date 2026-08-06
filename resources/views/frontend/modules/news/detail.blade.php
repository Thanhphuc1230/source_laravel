@extends('frontend.master')
@section('module', lang($news_detail, 'name'))
@section('keywords', lang($news_detail, 'keyword'))
@section('description', lang($news_detail, 'description'))
@section('images', lang($news_detail, 'image') ?? $web->logo)

@section('content')
    @if(!empty($news_settings['banner_detail']))
        <div class="bg-slate-900 text-white py-14 text-center space-y-2 relative overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center opacity-40" style="background-image: url('{{ asset($news_settings['banner_detail']) }}');"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/70 to-slate-900/40"></div>
            <div class="relative z-10 max-w-7xl mx-auto px-4 space-y-2">
                <div class="text-2xs text-gray-300 flex items-center justify-center space-x-2">
                    <a href="{{ route('web.home') }}" class="hover:text-white">Trang chủ</a>
                    <i class="fa-solid fa-chevron-right text-3xs"></i>
                    @if($news_detail->cate)
                        <a href="{{ route('web.resolve', ['slug' => $news_detail->cate->slug]) }}" class="hover:text-white">{{ lang($news_detail->cate, 'name') }}</a>
                        <i class="fa-solid fa-chevron-right text-3xs"></i>
                    @endif
                    <span class="text-gold-400 font-semibold truncate">{{ lang($news_detail, 'name') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-white max-w-4xl mx-auto leading-snug pt-2">
                    {{ lang($news_detail, 'name') }}
                </h1>
            </div>
        </div>
    @else
        <div class="bg-gray-100 py-4 border-b border-gray-200/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-2xs text-gray-500 flex items-center space-x-2">
                <a href="{{ route('web.home') }}" class="hover:text-emerald-950">Trang chủ</a>
                <i class="fa-solid fa-chevron-right text-3xs"></i>
                @if($news_detail->cate)
                    <a href="{{ route('web.resolve', ['slug' => $news_detail->cate->slug]) }}" class="hover:text-emerald-950">{{ lang($news_detail->cate, 'name') }}</a>
                    <i class="fa-solid fa-chevron-right text-3xs"></i>
                @endif
                <span class="text-gray-700 font-semibold truncate">{{ lang($news_detail, 'name') }}</span>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Article content (col-span-2) -->
            <div class="lg:col-span-2 space-y-6">
                <article class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="space-y-3">
                        <span class="text-gold-650 text-2xs font-bold uppercase tracking-wider block">
                            {{ $news_detail->created_at ? $news_detail->created_at->format('d/m/Y') : '' }}
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-emerald-950 leading-tight">
                            {{ lang($news_detail, 'name') }}
                        </h1>
                        <p class="text-xs text-gray-500 font-semibold italic leading-relaxed border-l-4 border-gold-500 pl-4">
                            {{ lang($news_detail, 'intro') }}
                        </p>
                    </div>

                    <!-- Main Article Image -->
                    <div class="rounded-xl overflow-hidden shadow-sm h-[250px] sm:h-[400px]">
                        <img src="{{ asset($news_detail->image_vn) }}" alt="{{ lang($news_detail, 'name') }}" class="w-full h-full object-cover">
                    </div>

                    <!-- Article Body HTML -->
                    <div class="prose prose-sm max-w-none text-xs text-gray-705 leading-relaxed space-y-4">
                        {!! lang($news_detail, 'content') !!}
                    </div>
                </article>
            </div>

            <!-- Right Column: Sidebar (Related News & Hot Tours) -->
            <div class="space-y-6">
                <!-- Related News Widget -->
                @if(isset($related_news) && $related_news->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-4">
                        <h3 class="font-heading font-bold text-sm text-emerald-950 border-b border-gray-100 pb-3 flex items-center">
                            <i class="fa-solid fa-paperclip text-emerald-800 mr-2"></i>
                            <span>Bài viết cùng chuyên mục</span>
                        </h3>
                        <div class="space-y-4">
                            @foreach($related_news as $rel)
                                <div class="flex items-center space-x-3 group">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        <a href="{{ route('web.resolve', ['slug' => lang($rel, 'slug')]) }}">
                                            <img src="{{ asset($rel->image_vn) }}" alt="{{ lang($rel, 'name') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </a>
                                    </div>
                                    <div class="space-y-0.5 min-w-0">
                                        <span class="text-4xs text-gray-400 block">{{ $rel->created_at ? $rel->created_at->format('d/m/Y') : '' }}</span>
                                        <h4 class="font-bold text-2xs text-emerald-950 leading-snug line-clamp-2 hover:text-emerald-700 transition-colors">
                                            <a href="{{ route('web.resolve', ['slug' => lang($rel, 'slug')]) }}">
                                                {{ lang($rel, 'name') }}
                                            </a>
                                        </h4>
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
