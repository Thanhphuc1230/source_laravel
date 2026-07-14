@extends('frontend.master')
@section('module', lang($page_detail, 'name'))
@section('keywords', lang($page_detail, 'keyword'))
@section('description', lang($page_detail, 'description'))
@section('images', asset(lang($page_detail, 'image') ?? 'images/logo/' . $web->logo))

@section('content')
    <div class="bg-gray-100 py-4 border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-2xs text-gray-500 flex items-center space-x-2">
            <a href="{{ route('web.home') }}" class="hover:text-emerald-950">Trang chủ</a>
            <i class="fa-solid fa-chevron-right text-3xs"></i>
            <span class="text-gray-700 font-semibold truncate">{{ lang($page_detail, 'name') }}</span>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <article class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-10 shadow-sm space-y-6">
            <h1 class="text-3xl font-heading font-extrabold text-emerald-950 text-center border-b border-gray-100 pb-4">
                {{ lang($page_detail, 'name') }}
            </h1>
            
            <div class="prose prose-sm max-w-none text-xs text-gray-750 leading-relaxed space-y-4 pt-4">
                {!! lang($page_detail, 'content') !!}
            </div>
        </article>
    </div>
@endsection
