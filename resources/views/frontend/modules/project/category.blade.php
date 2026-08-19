@extends('frontend.master')
@section('module', lang($category_detail, 'name'))
@section('keywords', lang($category_detail, 'keyword'))
@section('description', lang($category_detail, 'description'))
@section('images', lang($category_detail, 'image') ?? $web->logo)

@section('content')
    <div class="bg-slate-900 text-white py-16 text-center space-y-2 relative overflow-hidden">
        <div class="relative z-10 max-w-7xl mx-auto px-4 space-y-2">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Dự án tiêu biểu</span>
            <h1 class="text-3xl font-heading font-extrabold">{{ lang($category_detail, 'name') }}</h1>
            <p class="text-3xs text-gray-300">Các công trình & dự án tiêu biểu đã được chúng tôi thực hiện</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Projects Grid (col-span-2) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @if($projects->isNotEmpty())
                        @foreach($projects as $item)
                            @php
                                $itemName = lang($item, 'name');
                                $itemSlug = lang($item, 'slug');
                                $itemImage = $item->image;
                                $itemDate = $item->created_at ? $item->created_at->format('d/m/Y') : '';
                            @endphp
                            <article class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-2xs hover:shadow-md transition-all duration-300 flex flex-col justify-between group h-full">
                                <div class="relative h-48 overflow-hidden bg-gray-200">
                                    <a href="{{ route('web.resolve', ['slug' => $itemSlug]) }}" class="block w-full h-full">
                                        <img src="{{ $itemImage }}" alt="{{ $itemName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </a>
                                    <span class="absolute bottom-4 left-4 bg-emerald-950 text-white text-3xs font-bold px-2.5 py-1 rounded-md tracking-wide">
                                        {{ $itemDate }}
                                    </span>
                                </div>
                                <div class="p-5 flex-grow flex flex-col justify-between">
                                    <div class="space-y-2">
                                        <h3 class="font-heading font-bold leading-snug group-hover:text-emerald-700 transition-colors line-clamp-2 text-sm text-emerald-950">
                                            <a href="{{ route('web.resolve', ['slug' => $itemSlug]) }}">
                                                {{ $itemName }}
                                            </a>
                                        </h3>
                                        <p class="text-3xs text-gray-500 leading-relaxed line-clamp-2">
                                            {{ strip_tags(lang($item, 'intro')) }}
                                        </p>
                                    </div>
                                    <div class="pt-4">
                                        <a href="{{ route('web.resolve', ['slug' => $itemSlug]) }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-emerald-900 group-hover:text-emerald-600 transition-colors">
                                            <span>Xem chi tiết</span>
                                            <i class="fa-solid fa-chevron-right text-3xs"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    @else
                        <div class="col-span-2 bg-white p-12 text-center border border-gray-100 rounded-2xl">
                            <p class="text-xs text-gray-400">Không có dự án nào thuộc danh mục này.</p>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if($projects->hasPages())
                    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex justify-center mt-6">
                        {!! $projects->links('frontend.partials.pagination') !!}
                    </div>
                @endif
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                @if(isset($product_hot) && $product_hot->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-4">
                        <h3 class="font-heading font-bold text-sm text-emerald-950 border-b border-gray-100 pb-3 flex items-center">
                            <i class="fa-solid fa-fire text-red-500 mr-2"></i>
                            <span>Sản phẩm nổi bật</span>
                        </h3>
                        <div class="space-y-4">
                            @foreach($product_hot as $prod)
                                <div class="flex items-center space-x-3 group">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        <a href="{{ route('web.resolve', ['slug' => $prod->slug_vn]) }}">
                                            <img src="{{ $prod->image }}" alt="{{ $prod->name_vn }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </a>
                                    </div>
                                    <div class="space-y-1 min-w-0">
                                        <h4 class="font-bold text-2xs text-emerald-950 leading-snug line-clamp-2 hover:text-emerald-700 transition-colors">
                                            <a href="{{ route('web.resolve', ['slug' => $prod->slug_vn]) }}">
                                                {{ $prod->name_vn }}
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
