<footer class="bg-emerald-950 text-gray-300 pt-16 pb-8 border-t border-emerald-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <!-- Brand Widget -->
            <div class="space-y-4">
                <a href="{{ route('web.home') }}" class="text-2xl font-heading font-extrabold tracking-wider text-white">
                    <span>BASE</span>
                    <span class="text-gold-500">TRAVEL</span>
                </a>
                <p class="text-xs leading-relaxed text-gray-400">
                    {{ $web->footer_vn  }}
                </p>
                <div class="flex space-x-3 pt-2">
                    <a href="{{ $web->facebook ?? '#' }}" target="_blank" class="w-8 h-8 rounded-full bg-emerald-900 flex items-center justify-center text-white hover:bg-gold-500 hover:text-emerald-950 transition-all duration-300">
                        <i class="fa-brands fa-facebook-f text-sm"></i>
                    </a>
                    <a href="{{ $web->youtube ?? '#' }}" target="_blank" class="w-8 h-8 rounded-full bg-emerald-900 flex items-center justify-center text-white hover:bg-gold-500 hover:text-emerald-950 transition-all duration-300">
                        <i class="fa-brands fa-youtube text-sm"></i>
                    </a>
                    <a href="mailto:{{ $web->email ?? 'info@haothientravel.com' }}" class="w-8 h-8 rounded-full bg-emerald-900 flex items-center justify-center text-white hover:bg-gold-500 hover:text-emerald-950 transition-all duration-300">
                        <i class="fa-solid fa-envelope text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Products Footer Links -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-3 border-gold-500 pl-3">Tour Du Lịch</h4>
                <ul class="space-y-2.5 text-xs">
                    @if(isset($category_product_footer) && $category_product_footer->isNotEmpty())
                        @foreach($category_product_footer as $cate)
                            <li>
                                <a href="{{ route('web.resolve', ['slug' => $cate->slug]) }}" class="hover:text-gold-500 transition-colors duration-200">
                                    {{ lang($cate, 'name') }}
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li><a href="#" class="hover:text-gold-500 transition-colors">Tour Trong Nước</a></li>
                        <li><a href="#" class="hover:text-gold-500 transition-colors">Tour Quốc Tế</a></li>
                        <li><a href="#" class="hover:text-gold-500 transition-colors">Tour Cao Cấp</a></li>
                    @endif
                </ul>
            </div>

            <!-- Page / Info Links -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-3 border-gold-500 pl-3">Thông Tin</h4>
                <ul class="space-y-2.5 text-xs">
                    @if(isset($footer_pages) && $footer_pages->isNotEmpty())
                        @foreach($footer_pages as $page)
                            <li>
                                <a href="{{ route('web.resolve', ['slug' => $page->slug]) }}" class="hover:text-gold-500 transition-colors duration-200">
                                    {{ lang($page, 'name') }}
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li><a href="{{ route('web.contact') }}" class="hover:text-gold-500 transition-colors">Liên hệ tư vấn</a></li>
                        <li><a href="#" class="hover:text-gold-500 transition-colors">Chính sách bảo mật</a></li>
                        <li><a href="#" class="hover:text-gold-500 transition-colors">Điều khoản dịch vụ</a></li>
                    @endif
                </ul>
            </div>

            <!-- Subscribe Form Widget -->
            <div>
                <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4 border-l-3 border-gold-500 pl-3">Đăng ký nhận tin</h4>
                <p class="text-xs text-gray-400 mb-3 leading-relaxed">
                    Nhập email của bạn để nhận những chương trình khuyến mãi và tour du lịch mới nhất.
                </p>
                <form action="{{ route('web.postSubscribe') }}" method="POST" class="space-y-2">
                    @csrf
                    <div class="relative">
                        <input type="email" name="email" required placeholder="Email của bạn..." class="w-full bg-emerald-900 border border-emerald-800 text-white rounded-lg pl-4 pr-10 py-2.5 text-xs focus:outline-none focus:border-gold-500 placeholder-emerald-400">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-gold-500 hover:text-white transition-colors">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="border-t border-emerald-900/60 pt-8 mt-8 flex flex-col md:flex-row items-center justify-between text-xs text-gray-400">
            <p>&copy; 2026 BASE TRAVEL. All rights reserved. Designed with premium aesthetics.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <p><i class="fa-solid fa-location-dot mr-1"></i> {{ $web->address ?? 'Lầu 5, Tòa nhà Travel, TP. Hồ Chí Minh' }}</p>
                <p><i class="fa-solid fa-phone mr-1"></i> {{ $web->phone ?? '1800 6700' }}</p>
            </div>
        </div>
    </div>
</footer>
