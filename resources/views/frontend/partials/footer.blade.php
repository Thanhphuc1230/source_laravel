<footer class="bg-slate-950 text-slate-300 pt-16 pb-8 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            <!-- Brand Widget -->
            <div class="space-y-4">
                <a href="{{ route('web.home') }}" class="flex items-center space-x-2 group">
                    @if(!empty($web->logo))
                        <img src="{{ asset($web->logo) }}" alt="{{ $web->name_vn ?? 'Luxury Watches' }}" class="h-10 w-auto object-contain">
                    @else
                        <span class="text-xl font-heading font-extrabold tracking-widest uppercase text-white">
                            {{ $web->name_vn ?? 'AURA WATCHES' }}
                        </span>
                    @endif
                </a>
                <p class="text-xs leading-relaxed text-slate-400">
                    {{ $web->footer_vn ?? ($web->intro_vn ?? 'Tuyệt tác đồng hồ cao cấp khẳng định đẳng cấp và phong cách sống thượng lưu.') }}
                </p>
                <div class="flex space-x-3 pt-2">
                    @if(!empty($web->facebook))
                    <a href="{{ $web->facebook }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-900 border border-slate-700 flex items-center justify-center text-slate-300 hover:text-slate-950 hover:bg-gold-500 hover:border-gold-500 transition-all duration-300 shadow-2xs">
                        <i class="fa-brands fa-facebook-f text-xs"></i>
                    </a>
                    @endif
                    @if(!empty($web->youtube))
                    <a href="{{ $web->youtube }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-900 border border-slate-700 flex items-center justify-center text-slate-300 hover:text-slate-950 hover:bg-gold-500 hover:border-gold-500 transition-all duration-300 shadow-2xs">
                        <i class="fa-brands fa-youtube text-xs"></i>
                    </a>
                    @endif
                    @if(!empty($web->email))
                    <a href="mailto:{{ $web->email }}" class="w-8 h-8 rounded-full bg-slate-900 border border-slate-700 flex items-center justify-center text-slate-300 hover:text-slate-950 hover:bg-gold-500 hover:border-gold-500 transition-all duration-300 shadow-2xs">
                        <i class="fa-solid fa-envelope text-xs"></i>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Products / Collections Links -->
            <div>
                <h4 class="text-white font-heading font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-gold-500 pl-3">
                    Bộ Sưu Tập
                </h4>
                <ul class="space-y-2.5 text-xs">
                    @if(isset($category_product_footer) && $category_product_footer->isNotEmpty())
                        @foreach($category_product_footer as $cate)
                            <li>
                                <a href="{{ route('web.resolve', ['slug' => $cate->slug]) }}" class="text-slate-400 hover:text-gold-400 transition-colors duration-200 flex items-center group">
                                    <i class="fa-solid fa-angle-right text-3xs text-gold-500 mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    {{ lang($cate, 'name') }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <!-- Page / Info Links -->
            <div>
                <h4 class="text-white font-heading font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-gold-500 pl-3">
                    Thông Tin & Hỗ Trợ
                </h4>
                <ul class="space-y-2.5 text-xs">
                    @if(isset($footer_pages) && $footer_pages->isNotEmpty())
                        @foreach($footer_pages as $page)
                            <li>
                                <a href="{{ route('web.resolve', ['slug' => $page->slug]) }}" class="text-slate-400 hover:text-gold-400 transition-colors duration-200 flex items-center group">
                                    <i class="fa-solid fa-angle-right text-3xs text-gold-500 mr-2 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    {{ lang($page, 'name') }}
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <a href="{{ route('web.contact') }}" class="text-slate-400 hover:text-gold-400 transition-colors duration-200">
                                Liên hệ tư vấn
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Subscribe Form Widget -->
            <div>
                <h4 class="text-white font-heading font-bold text-sm uppercase tracking-wider mb-4 border-l-2 border-gold-500 pl-3">
                    Bản Tin Độc Quyền
                </h4>
                <p class="text-xs text-slate-400 mb-3 leading-relaxed">
                    Đăng ký để nhận thông tin về các kiệt tác đồng hồ mới nhất và ưu đãi đặc quyền.
                </p>
                <form action="{{ route('web.postSubscribe') }}" method="POST" class="space-y-2">
                    @csrf
                    <div class="relative">
                        <input type="email" name="email" required placeholder="Nhập địa chỉ email..." class="w-full bg-slate-900 border border-slate-800 text-white rounded-lg pl-4 pr-10 py-2.5 text-xs focus:outline-none focus:border-gold-500 placeholder-slate-500 transition-colors">
                        <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gold-500 hover:text-gold-300 transition-colors p-1" title="Gửi">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="border-t border-slate-800/80 pt-6 mt-6 flex flex-col md:flex-row items-center justify-between text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} {{ $web->name_vn ?? 'Luxury Watches' }}. All rights reserved.</p>
            <div class="flex flex-wrap gap-4 mt-3 md:mt-0 text-xs">
                @if(!empty($web->address_vn ?? $web->address))
                <p class="flex items-center"><i class="fa-solid fa-location-dot text-gold-500 mr-1.5"></i> {{ $web->address_vn ?? $web->address }}</p>
                @endif
                @if(!empty($web->phone))
                <p class="flex items-center"><i class="fa-solid fa-phone text-gold-500 mr-1.5"></i> {{ $web->phone }}</p>
                @endif
            </div>
        </div>
    </div>
</footer>
