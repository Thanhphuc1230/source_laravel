@extends('frontend.master')
@section('module', lang($category_detail, 'name'))
@section('keywords', lang($category_detail, 'keyword'))
@section('description', lang($category_detail, 'description'))
@section('images', $category_detail->image_vn ?? $web->logo)

@section('content')
    <!-- Category Page Header Banner -->
    <div class="bg-emerald-950 text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center opacity-25" style="background-image: url('{{ $category_detail->image ?? asset('uploads/slider/slide_1.jpg') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-emerald-950 via-emerald-950/60 to-emerald-950/30"></div>
        <div class="max-w-7xl mx-auto px-4 relative z-10 text-center space-y-2">
            <span class="text-gold-500 text-xs font-bold uppercase tracking-widest">Danh mục du lịch</span>
            <h1 class="text-3xl md:text-4xl font-heading font-extrabold">{{ lang($category_detail, 'name') }}</h1>
            <p class="max-w-xl mx-auto text-xs text-gray-300 leading-relaxed">{{ lang($category_detail, 'description') }}</p>
        </div>
    </div>

    <!-- Category Content Listing Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar Left Column (Filters & Categories) -->
            <div class="space-y-6 lg:col-span-1">
                <!-- Categories List Widget -->
                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm space-y-4">
                    <h3 class="font-heading font-bold text-sm text-emerald-950 border-b border-gray-100 pb-3 flex items-center">
                        <i class="fa-solid fa-list-ul mr-2 text-emerald-800"></i>
                        <span>Danh mục Tour</span>
                    </h3>
                    <ul class="space-y-2">
                        @if(isset($category_product[0]) && count($category_product[0]) > 0)
                            @foreach($category_product[0] as $parent)
                                <li>
                                    <a href="{{ route('web.resolve', ['slug' => $parent->slug]) }}" class="flex items-center justify-between py-1 text-xs font-semibold hover:text-emerald-900 transition-colors {{ $parent->id_cate_product == $category_detail->id_cate_product ? 'text-emerald-900 font-extrabold border-l-2 border-emerald-900 pl-2' : 'text-gray-600' }}">
                                        <span>{{ lang($parent, 'name') }}</span>
                                        <span class="bg-gray-100 text-gray-500 text-4xs px-2 py-0.5 rounded-full font-bold">{{ $parent->products->count() }}</span>
                                    </a>

                                    <!-- Children categories -->
                                    @if(isset($category_product[$parent->id_cate_product]) && count($category_product[$parent->id_cate_product]) > 0)
                                        <ul class="pl-4 mt-1.5 space-y-1.5 border-l border-gray-100">
                                            @foreach($category_product[$parent->id_cate_product] as $child)
                                                <li>
                                                    <a href="{{ route('web.resolve', ['slug' => $child->slug]) }}" class="flex items-center justify-between text-2xs hover:text-emerald-900 transition-colors {{ $child->id_cate_product == $category_detail->id_cate_product ? 'text-emerald-900 font-extrabold' : 'text-gray-500' }}">
                                                        <span>{{ lang($child, 'name') }}</span>
                                                        <span class="text-gray-400 font-medium">({{ $child->products->count() }})</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <!-- Price Filter Form Widget -->
                <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <h3 class="font-heading font-bold text-sm text-emerald-950 border-b border-gray-100 pb-3 mb-4 flex items-center">
                        <i class="fa-solid fa-sliders mr-2 text-emerald-800"></i>
                        <span>Bộ lọc tìm kiếm</span>
                    </h3>
                    <form action="{{ request()->url() }}" method="GET" class="space-y-4">
                        <!-- Keep order sort values if active -->
                        @if(request()->filled('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <!-- Text Search -->
                        <div class="space-y-1.5">
                            <label class="text-3xs font-extrabold uppercase text-gray-500">Tên Tour</label>
                            <input type="text" name="name" value="{{ request('name') }}" placeholder="Nhập từ khóa tìm kiếm..." class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-emerald-600">
                        </div>

                        <!-- Price Ranges inputs -->
                        <div class="space-y-1.5">
                            <label class="text-3xs font-extrabold uppercase text-gray-500">Giá bán (VNĐ)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Tối thiểu" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-emerald-600">
                                <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Tối đa" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-emerald-600">
                            </div>
                        </div>

                        <div class="pt-2 flex gap-2">
                            <button type="submit" class="flex-grow bg-emerald-950 hover:bg-emerald-900 text-white font-bold text-xs py-2.5 rounded-lg transition-colors">
                                Lọc kết quả
                            </button>
                            <a href="{{ request()->url() }}" class="bg-gray-100 hover:bg-gray-250 text-emerald-950 font-bold text-xs px-3 py-2.5 rounded-lg transition-colors flex items-center justify-center" title="Xóa bộ lọc">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Listing Column (Right Column) -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Sorting & Top Bar -->
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-xs text-gray-500 font-medium">
                        Hiển thị <span class="text-emerald-950 font-bold">{{ $products->count() }}</span> / {{ $products->total() }} tour du lịch
                    </p>

                    <!-- Sorting options drop down -->
                    <div class="flex items-center space-x-3 self-end sm:self-auto">
                        <span class="text-3xs font-extrabold uppercase text-gray-400">Sắp xếp:</span>
                        <div class="relative">
                            <select onchange="location = this.value;" class="bg-gray-50 border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-xs text-gray-700 font-bold focus:outline-none focus:border-emerald-600 appearance-none cursor-pointer">
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'default']) }}" {{ request('sort') == 'default' ? 'selected' : '' }}>Mặc định</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A - Z</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_desc']) }}" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z - A</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'date_desc']) }}" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Mới nhất</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-3xs text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @if($products->isNotEmpty())
                        @foreach($products as $tour)
                            <div class="w-full">
                                @include('frontend.partials.tour_card', ['tour' => $tour])
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-3 bg-white p-12 text-center border border-gray-100 rounded-2xl">
                            <i class="fa-solid fa-route text-3xl text-gray-300 mb-3 block"></i>
                            <p class="text-xs text-gray-400">Không tìm thấy tour du lịch nào phù hợp với bộ lọc.</p>
                            <a href="{{ request()->url() }}" class="inline-block mt-4 bg-emerald-950 text-white font-bold text-xs px-5 py-2.5 rounded-lg hover:bg-emerald-900 transition-colors">
                                Xóa tất cả bộ lọc
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Pagination controls -->
                @if($products->hasPages())
                    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex justify-center">
                        <div class="pagination-wrap w-full">
                            {!! $products->links('frontend.partials.pagination') !!}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
