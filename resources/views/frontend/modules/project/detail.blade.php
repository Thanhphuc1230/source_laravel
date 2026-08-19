@extends('frontend.master')
@section('module', lang($project_detail, 'name'))
@section('keywords', lang($project_detail, 'keyword'))
@section('description', lang($project_detail, 'description'))
@section('images', lang($project_detail, 'image') ?? $web->logo)

@section('content')
    <div class="bg-gray-100 py-4 border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-2xs text-gray-500 flex items-center space-x-2">
            <a href="{{ route('web.home') }}" class="hover:text-emerald-950">Trang chủ</a>
            <i class="fa-solid fa-chevron-right text-3xs"></i>
            @if($project_detail->cate)
                <a href="{{ route('web.resolve', ['slug' => $project_detail->cate->slug]) }}" class="hover:text-emerald-950">{{ lang($project_detail->cate, 'name') }}</a>
                <i class="fa-solid fa-chevron-right text-3xs"></i>
            @endif
            <span class="text-gray-700 font-semibold truncate">{{ lang($project_detail, 'name') }}</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Article content (col-span-2) -->
            <div class="lg:col-span-2 space-y-6">
                <article class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="space-y-3">
                        <span class="text-emerald-600 text-2xs font-bold uppercase tracking-wider block">
                            {{ $project_detail->created_at ? $project_detail->created_at->format('d/m/Y') : '' }}
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-emerald-950 leading-tight">
                            {{ lang($project_detail, 'name') }}
                        </h1>
                        <p class="text-xs text-gray-500 font-semibold italic leading-relaxed border-l-4 border-emerald-500 pl-4">
                            {{ lang($project_detail, 'intro') }}
                        </p>
                    </div>

                    <!-- Main Image -->
                    <div class="rounded-xl overflow-hidden shadow-sm h-[250px] sm:h-[400px]">
                        <img src="{{ $project_detail->image }}" alt="{{ lang($project_detail, 'name') }}" class="w-full h-full object-cover">
                    </div>

                    <!-- Article Body HTML -->
                    <div class="prose prose-sm max-w-none text-xs text-gray-700 leading-relaxed space-y-4">
                        {!! lang($project_detail, 'content') !!}
                    </div>
                </article>
            </div>

            <!-- Right Column: Sidebar (Related Projects) -->
            <div class="space-y-6">
                @if(isset($related_projects) && $related_projects->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-4">
                        <h3 class="font-heading font-bold text-sm text-emerald-950 border-b border-gray-100 pb-3 flex items-center">
                            <i class="fa-solid fa-diagram-project text-emerald-800 mr-2"></i>
                            <span>Dự án liên quan</span>
                        </h3>
                        <div class="space-y-4">
                            @foreach($related_projects as $rel)
                                <div class="flex items-center space-x-3 group">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        <a href="{{ route('web.resolve', ['slug' => lang($rel, 'slug')]) }}">
                                            <img src="{{ $rel->image }}" alt="{{ lang($rel, 'name') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
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
