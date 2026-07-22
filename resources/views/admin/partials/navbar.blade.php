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
                    <a class="nav-link menu-link" href="{{ route('web.home') }}">
                        <i class="ri-home-line"></i> <span data-key="t-widgets">Trang chủ</span>
                    </a>
                </li>
                @hasPermission('analytics.view')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.analytics.index') }}">
                        <i class="ri-bar-chart-line"></i> <span data-key="t-widgets">Thống kê</span>
                    </a>
                </li>
                @endhasPermission
                @hasPermission('page.view')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.page.index') }}">
                        <i class="ri-file-list-3-line"></i> <span data-key="t-widgets">Trang nội dung</span>
                    </a>
                </li>
                @endhasPermission
                @hasPermission('slider.view')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.slider.index') }}">
                        <i class="ri-image-edit-line"></i> <span data-key="t-widgets">Slider</span>
                    </a>
                </li>
                @endhasPermission
                @hasPermission('gallery.view')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.gallery.index') }}">
                        <i class="ri-gallery-line"></i> <span data-key="t-widgets">Hình ảnh</span>
                    </a>
                </li>
                @endhasPermission
                @hasPermission('brand.view')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.brand.index') }}">
                        <i class="ri-building-line"></i> <span data-key="t-widgets">Đối tác</span>
                    </a>
                </li>
                @endhasPermission
                @hasPermission('feature.view')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.feature.index') }}">
                        <i class="ri-star-line"></i> <span data-key="t-widgets">Tính năng</span>
                    </a>
                </li>
                @endhasPermission
                @hasAnyPermission(['menu.view', 'font.view'])
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarLayouts">
                        <i class="ri-layout-4-line"></i> <span data-key="t-layouts">Giao diện</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarLayouts">
                        <ul class="nav nav-sm flex-column">
                            @hasPermission('menu.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.menu.index') }}" class="nav-link" data-key="t-calendar">Menu
                                </a>
                            </li>
                            @endhasPermission
                            @hasPermission('font.view')
                            <li class="nav-item">
                                <a href="{{ route('admin.fonts.index') }}" class="nav-link" data-key="t-fonts">Quản lý Font
                                </a>
                            </li>
                            @endhasPermission
                        </ul>
                    </div>
                </li>
                @endhasAnyPermission
                @hasAnyPermission(['cate_product.view', 'product.view'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarProduct" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarProduct">
                            <i class="ri-shopping-bag-3-line"></i> <span data-key="t-tables">Sản phẩm</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarProduct">
                            <ul class="nav nav-sm flex-column">
                                @hasPermission('cate_product.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.cate_product.index') }}" class="nav-link"
                                        data-key="t-basic-tables">Danh mục </a>
                                </li>
                                @endhasPermission
                                @hasPermission('product.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.product.index') }}" class="nav-link"
                                        data-key="t-grid-js">Bài viết</a>
                                </li>
                                @endhasPermission
                                @hasPermission('order.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.order.index') }}" class="nav-link" data-key="t-grid-js">Đơn
                                        hàng</a>
                                </li>
                                @endhasPermission
                                @hasPermission('product_setting.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.product-setting.index') }}" class="nav-link"
                                        data-key="t-grid-js">Cài đặt</a>
                                </li>
                                @endhasPermission
                            </ul>
                        </div>
                    </li>
                @endhasAnyPermission
                @hasAnyPermission(['cate_news.view', 'news.view'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarNews" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarNews">
                            <i class="ri-newspaper-line"></i> <span data-key="t-layouts">Tin tức</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarNews">
                            <ul class="nav nav-sm flex-column">
                                @hasPermission('cate_news.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.cate_new.index') }}" class="nav-link">Chủ đề</a>
                                </li>
                                @endhasPermission
                                @hasPermission('news.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.news.index') }}" class="nav-link">Bài viết</a>
                                </li>
                                @endhasPermission
                                @hasPermission('system.view')
                                <li class="nav-item">
                                    <a href="{{ route('admin.news-setting.index') }}" class="nav-link">Cài đặt</a>
                                </li>
                                @endhasPermission
                            </ul>
                        </div>
                    </li>
                @endhasAnyPermission
                @hasPermission('feedback.view')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('admin.feedback.index') }}">
                        <i class="ri-feedback-line"></i> <span data-key="t-widgets">Đánh giá khách hàng</span>
                    </a>
                </li>
                @endhasAnyPermission
                @hasAnyPermission(['contact.view', 'comment.view'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#Contact" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarNews">
                            <i class="ri-message-line"></i> <span data-key="t-layouts">Liên hệ</span>
                        </a>
                        <div class="collapse menu-dropdown" id="Contact">
                            <ul class="nav nav-sm flex-column">
                                @hasPermission('contact.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.contact.index') }}" class="nav-link">Liên hệ</a>
                                    </li>
                                @endhasPermission
                                @hasPermission('comment.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.comment.index') }}" class="nav-link">Bình luận</a>
                                    </li>
                                @endhasPermission
                            </ul>
                        </div>
                    </li>
                @endhasAnyPermission
                {{-- @hasPermission('chat.view')
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('admin.chat.index') }}">
                            <i class="ri-chat-1-line"></i> <span data-key="t-widgets">Chat hỗ trợ</span>
                            <span class="badge bg-danger ms-2" id="chat-unread-badge" style="display: none;">0</span>
                        </a>
                    </li>
                @endhasPermission --}}
                {{-- mail --}}
                @hasAnyPermission(['mail-config.view', 'mail-template.view'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarMail" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarMail">
                            <i class="ri-mail-line"></i> <span data-key="t-mail">Mail</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarMail">
                            <ul class="nav nav-sm flex-column">
                                @hasPermission('mail-config.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.mail-config.index') }}" class="nav-link"
                                            data-key="t-mail-config">Cấu hình mail
                                        </a>
                                    </li>
                                @endhasPermission
                                @hasPermission('mail-template.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.mail-template.index') }}" class="nav-link"
                                            data-key="t-mail-template">Template mail
                                        </a>
                                    </li>
                                @endhasPermission
                            </ul>
                        </div>
                    </li>
                @endhasAnyPermission
                {{-- system --}}
                @hasAnyPermission(['system.view', 'user.view', 'role.view'])
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarApps" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarApps">
                            <i class="ri-settings-3-line"></i> <span data-key="t-dashboards">Hệ thống</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarApps">
                            <ul class="nav nav-sm flex-column">
                                @hasPermission('system.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.system.index') }}" class="nav-link"
                                            data-key="t-calendar">Quản lý hệ thống
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.system.editContact') }}" class="nav-link"
                                            data-key="t-calendar">Cấu hình liên hệ
                                        </a>
                                    </li>
                                @endhasPermission
                                @hasPermission('user.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.user.index') }}" class="nav-link"
                                            data-key="t-user-management">Quản lý người dùng
                                        </a>
                                    </li>
                                @endhasPermission
                                @hasPermission('role.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.user-role.roles.index') }}" class="nav-link"
                                            data-key="t-roles">Quản lý vai trò
                                        </a>
                                    </li>
                                @endhasPermission
                                @hasPermission('user.view')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.user-role.index') }}" class="nav-link"
                                            data-key="t-user-role">Phân quyền người dùng
                                        </a>
                                    </li>
                                @endhasPermission
                                @hasPermission('system.edit')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.system.clearCache') }}" class="nav-link text-danger font-weight-bold"
                                            data-key="t-clear-cache">
                                            <i class="ri-delete-bin-line mr-1"></i> Xóa bộ nhớ đệm
                                        </a>
                                    </li>
                                @endhasPermission
                            </ul>
                        </div>
                    </li>
                @endhasAnyPermission
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
