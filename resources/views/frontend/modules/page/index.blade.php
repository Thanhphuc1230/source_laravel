@extends('frontend.master')
@section('module', lang($page_detail, 'name'))
@section('keywords', lang($page_detail, 'keyword'))
@section('description', lang($page_detail, 'description'))
@section('images', lang($page_detail, 'image') ?? $web->logo)

@section('content')
    <div class="bg-slate-100/70 py-3 border-b border-slate-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-2xs text-slate-500 flex items-center space-x-2">
            <a href="{{ route('web.home') }}" class="hover:text-gold-600 transition-colors">Trang chủ</a>
            <i class="fa-solid fa-chevron-right text-3xs text-slate-400"></i>
            <span class="text-slate-800 font-semibold truncate">{{ lang($page_detail, 'name') }}</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        <article class="bg-white rounded-2xl border border-slate-100 p-6 sm:p-10 md:p-14 shadow-xs space-y-6">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-heading font-extrabold text-slate-900 text-center border-b border-slate-100 pb-5">
                {{ lang($page_detail, 'name') }}
            </h1>
            
            <div class="prose prose-slate max-w-none text-xs sm:text-sm text-slate-700 leading-relaxed space-y-4 pt-2">
                {!! lang($page_detail, 'content') !!}
            </div>
        </article>
    </div>
@endsection
