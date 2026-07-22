@extends('admin.master')
@section('module', $title)
@section('action', 'Chi tiết')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $title }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Hệ thống</a></li>
                                <li class="breadcrumb-item active">{{ $title }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <form action="{{ route('admin.product-setting.update') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Column 1: Cấu hình chung -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold text-emerald-900">1. Cấu hình hiển thị</h5>
                            </div>
                            <div class="card-body">
                                <!-- Pagination -->
                                <div class="mb-3">
                                    <label class="form-label font-weight-semibold" for="products_pagination">
                                        Số lượng sản phẩm trên một trang <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="products_pagination" name="products_pagination"
                                        class="form-control" value="{{ $settings['pagination'] }}" min="1" max="100" required>
                                    <small class="text-muted">Đặt số lượng sản phẩm được phân trang ngoài frontend.</small>
                                </div>

                                <!-- Show Intro Switch -->
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-success py-2">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="products_show_intro" name="products_show_intro" value="1"
                                            {{ $settings['show_intro'] ? 'checked' : '' }}>
                                        <label class="form-check-label font-weight-semibold" for="products_show_intro">
                                            Hiển thị mô tả giới thiệu ngắn (Intro)
                                        </label>
                                    </div>
                                    <small class="text-muted d-block ms-5">Tích chọn để hiển thị phần mô tả ngắn gọn bên dưới tiêu đề sản phẩm.</small>
                                </div>

                                <!-- Click Image Detail Switch -->
                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-success py-2">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="products_click_image_detail" name="products_click_image_detail" value="1"
                                            {{ $settings['click_image_detail'] ? 'checked' : '' }}>
                                        <label class="form-check-label font-weight-semibold" for="products_click_image_detail">
                                            Click vào hình ảnh để xem chi tiết
                                        </label>
                                    </div>
                                    <small class="text-muted d-block ms-5">Nếu tắt, người dùng click vào ảnh sẽ không chuyển sang trang chi tiết sản phẩm.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Cấu hình giao diện, màu sắc -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold text-emerald-900">2. Cấu hình màu sắc & Kiểu chữ</h5>
                            </div>
                            <div class="card-body">
                                <!-- Font Size -->
                                <div class="mb-3">
                                    <label class="form-label font-weight-semibold" for="products_font_size">
                                        Kích thước tiêu đề (Font size)
                                    </label>
                                    <select class="form-control" id="products_font_size" name="products_font_size">
                                        @foreach(['12px', '14px', '16px', '18px', '20px', '22px'] as $size)
                                            <option value="{{ $size }}" {{ $settings['font_size'] == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Cỡ chữ tiêu đề sản phẩm khi hiển thị trên danh sách.</small>
                                </div>

                                <!-- Color Pickers -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-semibold" for="products_title_color">
                                            Màu sắc tiêu đề sản phẩm
                                        </label>
                                        <div class="d-flex align-items-center">
                                            <input type="color" id="products_title_color" name="products_title_color"
                                                class="form-control form-control-color border-0 me-2" value="{{ $settings['title_color'] }}" style="width: 50px; height: 38px;">
                                            <span class="text-muted font-monospace">{{ strtoupper($settings['title_color']) }}</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label font-weight-semibold" for="products_category_color">
                                            Màu sắc tên danh mục
                                        </label>
                                        <div class="d-flex align-items-center">
                                            <input type="color" id="products_category_color" name="products_category_color"
                                                class="form-control form-control-color border-0 me-2" value="{{ $settings['category_color'] }}" style="width: 50px; height: 38px;">
                                            <span class="text-muted font-monospace">{{ strtoupper($settings['category_color']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success px-4 py-2 waves-effect waves-light shadow-sm" style="transition: all 0.3s ease;">
                            <i class="ri-save-line align-bottom me-1"></i> Lưu cấu hình
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
