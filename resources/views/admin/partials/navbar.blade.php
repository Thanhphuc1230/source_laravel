<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('admin.analytics.index') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('images/bg/logo.png') }}" alt="" style="height: 50px">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('images/bg/logo.png') }}" alt="" style="height: 50px">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('admin.analytics.index') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('images/bg/logo.png') }}" alt="" style="height: 50px">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('images/bg/logo.png') }}" alt="" style="height: 50px">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.analytics.index') }}">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-widgets">Thống kê</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.page.index') }}">
                        <i class="ri-pages-line"></i> <span data-key="t-widgets">Trang nội dung</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-honour-line"></i></i> <span data-key="t-layouts">Giao diện</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarLayouts">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.menu.index') }}" class="nav-link" data-key="t-calendar">Menu
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarProduct" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarProduct">
                        <i class="ri-layout-grid-line"></i> <span data-key="t-tables">Sản phẩm</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarProduct">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.cate_product.index') }}" class="nav-link"
                                    data-key="t-basic-tables">Danh mục </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.product.index') }}" class="nav-link" data-key="t-grid-js">Bài viết</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarNews" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarNews">
                        <i class="ri-layout-3-line"></i> <span data-key="t-layouts">Tin tức</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarNews">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('admin.cate_new.index') }}" class="nav-link">Chủ đề</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.news.index') }}" class="nav-link">Bài viết</a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- system --}}
                @if (Auth::user()->level == 1)
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarApps" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarApps">
                            <i class="ri-share-line"></i> <span data-key="t-dashboards">Hệ thống</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarApps">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('admin.system.index') }}" class="nav-link"
                                        data-key="t-calendar">Quản
                                        lý hệ thống
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>