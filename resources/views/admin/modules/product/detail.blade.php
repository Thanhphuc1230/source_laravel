@extends('admin.master')
@section('module', $nameItem)
@section('action', $action == 'create' ? 'Thêm' : 'Chỉnh sửa')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}
                            {{ $nameItem }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Sản phẩm</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} sản phẩm
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <form
                action="{{ route('admin.' . $nameClass . ($action == 'create' ? '.store' : '.update'), ['uuid' => $page->uuid ?? '']) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                @include('admin.partials.localized-fields', [
                                    'fields' => [
                                        ['base' => 'name', 'label' => 'Tiêu đề', 'col' => 'col-md-12'],
                                        ['base' => 'intro', 'label' => 'Giới thiệu', 'col' => 'col-md-12', 'rows' => 4, 'type' => 'textarea'],
                                        ['base' => 'content', 'label' => 'Nội dung', 'col' => 'col-md-12', 'rows' => 6, 'type' => 'textarea', 'ckeditor' => true],
                                    ],
                                    'model' => $page ?? null,
                                ])
                            </div>
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#addproduct-general-info"
                                            role="tab" aria-selected="true">
                                            Thông tin tổng quan
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- end card header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="addproduct-general-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="product-price-input">Giá tiền(nhập 0 sẽ
                                                        hiện liên hệ)</label>
                                                    <div class="input-group has-validation mb-3">
                                                        <span class="input-group-text" id="product-price-addon">VND</span>
                                                        <input type="number"
                                                            class="form-control @error('price') is-invalid @enderror"
                                                            placeholder="Enter price" name="price"
                                                            value="{{ old('price', $page->price ?? '') }}">
                                                    </div>
                                                    @error('price')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="product-discount-input">Giá cũ</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text" id="product-price-addon">VND</span>
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter discount" name="price_old"
                                                            value="{{ old('price_old', $page->price_old ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end col -->
                                        </div>
                                        <!-- end row -->
                                    </div>
                                    <!-- end tab-pane -->
                                </div>
                                <!-- end tab content -->
                            </div>
                            <!-- end card body -->
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Hình ảnh sản phẩm</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($adminLanguages as $locale)
                                        @include('admin.partials.image-upload', [
                                            'locale' => $locale,
                                            'imageFolder' => $imageFolder,
                                            'model' => $page ?? null,
                                        ])
                                    @endforeach
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="mb-3">
                                        <label for="compnayNameinput" class="form-label">Hình ảnh chi tiết
                                            (280x280)</label>
                                        @error('image_detail')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        @include('admin.partials.multiImage')

                                        @if (!empty($page->image_detail))
                                            <div class="mt-3">
                                                <label class="form-label">Hình ảnh chi tiết hiện tại:</label>

                                                <div class="row" id="existing-images-container">
                                                    @foreach (json_decode($page->image_detail) as $index => $img_detail)
                                                        <div class="col-md-3 mb-2 existing-image-item" data-image="{{ $img_detail }}">
                                                            <div class="position-relative">
                                                                <img src="{{ str_starts_with($img_detail, 'uploads/') ? asset($img_detail) : asset('uploads/' . $imageFolder . '/' . $img_detail) }}"
                                                                    alt="Product detail"
                                                                    style="width: 200px; height: 200px;object-fit: contain"
                                                                    class="img-fluid rounded">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm remove-existing-image"
                                                                    style="position: absolute; top: 5px; right: 5px;">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                
                                                <!-- Hidden input để lưu danh sách hình ảnh được giữ lại -->
                                                <input type="hidden" name="kept_images" id="kept-images-input" 
                                                       value="{{ json_encode(json_decode($page->image_detail)) }}">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end card -->
                        <div class="text-end mb-3">
                            @if ($action == 'create')
                                <input type="submit" name="return_back" class="btn btn-primary" value="Lưu và tạo mới">
                                <input type="submit" name="return_list" class="btn btn-primary"
                                    value="Lưu và về danh sách">
                            @else
                                <input type="submit" name="return_list" class="btn btn-primary" value="Cập nhật">
                            @endif
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Hiển thị</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="choices-publish-status-input" class="form-label">Trạng thái</label>

                                    <select class="form-select" name="status">
                                        <option value="1"
                                            {{ (old('status') ?: $page->status ?? '') == 1 ? 'selected' : '' }}>Bật
                                        </option>
                                        <option value="0"
                                            {{ (old('status') ?: $page->status ?? '') == 0 ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="choices-publish-status-input" class="form-label">Nổi bật</label>

                                    <select class="form-select" name="hot">
                                        <option value="0"
                                            {{ (old('hot') ?: $page->hot ?? '') == 0 ? 'selected' : '' }}>
                                            Ẩn</option>
                                        <option value="1"
                                            {{ (old('hot') ?: $page->hot ?? '') == 1 ? 'selected' : '' }}>
                                            Bật</option>

                                    </select>
                                </div>

                                @include('admin.partials.publishing-fields', ['model' => $page ?? null, 'col' => 'col-md-12'])

                                @include('admin.partials.localized-fields', [
                                    'fields' => [
                                        ['base' => 'slug', 'label' => 'Slug', 'col' => 'col-md-12', 'type' => 'text'],
                                    ],
                                    'model' => $page ?? null,
                                ])

                                @include('admin.partials.preview-link', ['model' => $page ?? null])

                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">SEO</h5>
                            </div>
                            <div class="card-body">
                                @include('admin.partials.localized-fields', [
                                    'fields' => [
                                        ['base' => 'keyword', 'label' => 'Từ khóa', 'col' => 'col-md-12', 'rows' => 3, 'type' => 'textarea'],
                                        ['base' => 'description', 'label' => 'Mô tả ngắn', 'col' => 'col-md-12', 'rows' => 3, 'type' => 'textarea'],
                                    ],
                                    'model' => $page ?? null,
                                ])
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Chủ đề sản phẩm</h5>
                            </div>
                            <div class="card-body">
                                <select class="form-select" id="choices-category-input" name="category_id">
                                    @php
                                        renderCategoryOptions(
                                            $category,
                                            0,
                                            old('category_id') ?: $page->category_id ?? null,
                                            'id_cate_product',
                                        );
                                    @endphp
                                </select>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </form>

        </div>
        <!-- container-fluid -->
    </div>

    {{-- Logic xử lý hình ảnh chi tiết đã được di chuyển vào multiImage.blade.php --}}
    @include('admin.partials.ckeditor')
@endsection
