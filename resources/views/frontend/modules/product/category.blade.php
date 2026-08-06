@extends('frontend.master')
@section('module', lang($category_detail, 'name'))
@section('keywords', lang($category_detail, 'keyword'))
@section('description', lang($category_detail, 'description'))
@section('images', $category_detail->image ?? $web->logo)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Breadcrumb Navigation Bar (Single line, no wrap on mobile) -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6 font-medium whitespace-nowrap overflow-x-auto scrollbar-none py-1">
            <a href="{{ route('web.home') }}" class="hover:text-red-600 transition-colors">
                <i class="fas fa-home mr-1"></i> Trang Chủ
            </a>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <span class="text-slate-600">Thương Hiệu</span>
            <i class="fas fa-chevron-right text-[10px] text-slate-300"></i>
            <span class="text-red-600 font-bold">{{ lang($category_detail, 'name') }}</span>
        </nav>

        <!-- Category / Brand Header Banner -->
        <div class="bg-slate-900 text-white p-6 sm:p-8 rounded-2xl mb-8 relative overflow-hidden shadow-xl border border-slate-800">
            @if(!empty($product_settings['banner_category']))
                <div class="absolute inset-0 bg-cover bg-center opacity-25" style="background-image: url('{{ asset($product_settings['banner_category']) }}');"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/70 to-slate-900/30"></div>
            @endif
            <div class="relative z-10 space-y-3">
                <span class="bg-red-600 text-white text-[11px] font-extrabold uppercase px-3 py-1 rounded-md tracking-wider inline-block">
                    THƯƠNG HIỆU HỢP TÁC
                </span>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight">
                    Thương Hiệu {{ lang($category_detail, 'name') }}
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm max-w-2xl leading-relaxed">
                    {{ lang($category_detail, 'description') ?: 'Danh sách sản phẩm & dịch vụ thuộc thương hiệu ' . lang($category_detail, 'name') . ' với nhiều ưu đãi và dịch vụ chất lượng cao.' }}
                </p>
                <div class="pt-2 flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-300">
                    <span><i class="fas fa-layer-group text-red-500 mr-1.5"></i> Hiển thị: <strong class="text-white">{{ $products->total() }}</strong> sản phẩm</span>
                    <span><i class="fas fa-headset text-red-500 mr-1.5"></i> Hotline tư vấn: <a href="tel:{{ $web->phone }}" class="text-red-400 font-bold hover:underline">{{ $web->phone }}</a></span>
                </div>
            </div>
        </div>

        <!-- Main Listing Grid Layout (Sidebar + Products) -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar Left Column (Filters & Categories & Brands) -->
            <div class="space-y-6 lg:col-span-1">
                
                <!-- Brands Filter Widget -->
                @if(isset($brands) && $brands->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-3">
                        <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-building text-red-600"></i>
                                <span>Thương Hiệu</span>
                            </span>
                        </h3>
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            @foreach($brands as $b)
                                <a href="{{ route('web.resolve', ['slug' => $b->slug]) }}" 
                                   class="flex items-center gap-1.5 p-2 rounded-lg border border-slate-100 hover:border-red-600 text-xs font-semibold transition-all {{ isset($brand_detail) && $brand_detail->id_brand == $b->id_brand ? 'bg-red-50 border-red-600 text-red-600 font-bold' : 'text-slate-700 bg-slate-50' }}">
                                    @if($b->image)
                                        <img src="{{ $b->image }}" alt="{{ $b->name_vn }}" class="w-4 h-4 object-contain shrink-0">
                                    @endif
                                    <span class="truncate">{{ $b->name_vn }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Categories Widget -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm space-y-4">
                    <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3 flex items-center">
                        <i class="fas fa-list-ul mr-2 text-red-600"></i>
                        <span>Danh Mục Sản Phẩm</span>
                    </h3>
                    <ul class="space-y-2">
                        @if(isset($category_product[0]) && count($category_product[0]) > 0)
                            @foreach($category_product[0] as $parent)
                                <li>
                                    <a href="{{ route('web.resolve', ['slug' => $parent->slug]) }}" 
                                       class="flex items-center justify-between py-1 text-xs font-semibold hover:text-red-600 transition-colors {{ isset($category_detail->id_cate_product) && $parent->id_cate_product == $category_detail->id_cate_product ? 'text-red-600 font-extrabold border-l-2 border-red-600 pl-2' : 'text-slate-700' }}">
                                        <span>{{ lang($parent, 'name') }}</span>
                                        <span class="bg-slate-100 text-slate-500 text-[10px] px-2 py-0.5 rounded-full font-bold">{{ $parent->products->count() }}</span>
                                    </a>

                                    @if(isset($category_product[$parent->id_cate_product]) && count($category_product[$parent->id_cate_product]) > 0)
                                        <ul class="pl-4 mt-1.5 space-y-1.5 border-l border-slate-100">
                                            @foreach($category_product[$parent->id_cate_product] as $child)
                                                <li>
                                                    <a href="{{ route('web.resolve', ['slug' => $child->slug]) }}" class="flex items-center justify-between text-[11px] hover:text-red-600 transition-colors {{ isset($category_detail->id_cate_product) && $child->id_cate_product == $category_detail->id_cate_product ? 'text-red-600 font-extrabold' : 'text-slate-500' }}">
                                                        <span>{{ lang($child, 'name') }}</span>
                                                        <span class="text-slate-400 font-medium">({{ $child->products->count() }})</span>
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

                <!-- Search & Price Filter Form -->
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                    <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3 mb-4 flex items-center">
                        <i class="fas fa-sliders-h mr-2 text-red-600"></i>
                        <span>Bộ Lọc Tìm Kiếm</span>
                    </h3>
                    <form action="{{ request()->url() }}" method="GET" class="space-y-4">
                        @if(request()->filled('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-extrabold uppercase text-slate-500">Tên Sản Phẩm / Tour</label>
                            <input type="text" name="name" value="{{ request('name') }}" placeholder="Nhập từ khóa tìm kiếm..." class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-extrabold uppercase text-slate-500">Mức giá (VNĐ)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Từ" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                                <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Đến" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-red-600">
                            </div>
                        </div>

                        <div class="pt-2 flex gap-2">
                            <button type="submit" class="flex-grow bg-slate-900 hover:bg-red-600 text-white font-bold text-xs py-2.5 rounded-lg transition-colors shadow-sm">
                                <i class="fas fa-search mr-1"></i> Áp Dụng
                            </button>
                            <a href="{{ request()->url() }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs px-3 py-2.5 rounded-lg transition-colors flex items-center justify-center" title="Xóa lọc">
                                <i class="fas fa-redo"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid Column (Right Column) -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Sorting & Count Header Bar -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-xs text-slate-600 font-medium">
                        Hiển thị <span class="text-slate-900 font-extrabold">{{ $products->count() }}</span> / {{ $products->total() }} sản phẩm
                    </p>

                    <!-- Sorting Dropdown -->
                    <div class="flex items-center space-x-3 self-end sm:self-auto">
                        <span class="text-[10px] font-extrabold uppercase text-slate-400">Sắp xếp:</span>
                        <div class="relative">
                            <select onchange="location = this.value;" class="bg-slate-50 border border-slate-200 rounded-lg pl-3 pr-8 py-2 text-xs text-slate-800 font-bold focus:outline-none focus:border-red-600 appearance-none cursor-pointer">
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'default']) }}" {{ request('sort') == 'default' ? 'selected' : '' }}>Mặc định</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A - Z</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_desc']) }}" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z - A</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'date_desc']) }}" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Mới nhất</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- Products Grid: MANDATORY 2 COLUMNS ON MOBILE -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                    @if($products->isNotEmpty())
                        @foreach($products as $product)
                            <div class="w-full">
                                @include('frontend.partials.car_card', ['product' => $product])
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-2 md:col-span-3 lg:col-span-4 bg-white p-12 text-center border border-slate-200 rounded-2xl">
                            <i class="fas fa-folder-open text-4xl text-slate-300 mb-3 block"></i>
                            <p class="text-xs text-slate-500 font-medium">Chưa tìm thấy sản phẩm nào thuộc thương hiệu này.</p>
                            <a href="{{ request()->url() }}" class="inline-block mt-4 bg-slate-900 text-white font-bold text-xs px-5 py-2.5 rounded-lg hover:bg-red-600 transition-colors">
                                Xóa tất cả bộ lọc
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Pagination Links -->
                @if($products->hasPages())
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex justify-center">
                        <div class="pagination-wrap w-full flex justify-center">
                            {!! $products->links() !!}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
