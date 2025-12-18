@extends('frontend.master')
@section('module', $page_detail->name_vn)
@section('keywords', $page_detail->keywords)
@section('description', $page_detail->description)
@section('image', asset('images/page/' . $page_detail->image))
@section('content')
    <!-- Page Header -->
    <section class="bg-gradient-to-br from-[#0c4a5e] via-[#0a3d4f] to-[#083544] py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    {{ $page_detail->name_vn }}
                </h1>
            </div>
        </div>
    </section>
    
    <!-- Page Content -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="prose prose-lg max-w-none">
                    {!! $page_detail->content_vn !!}
                </div>
            </div>
        </div>
    </section>
@endsection