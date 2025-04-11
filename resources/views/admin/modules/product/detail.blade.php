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
                                <div class="mb-3">
                                    <label class="form-label" for="product-title-input">Tiêu đề VN</label>
                                    <input type="text" id="name_vn"
                                        class="form-control @error('name_vn') is-invalid @enderror" name="name_vn"
                                        value="{{ old('name_vn', $page->name_vn ?? '') }}"
                                        placeholder="Enter your title page ">
                                    @error('name_vn')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="product-title-input">Tiêu đề EN</label>
                                    <input type="text" id="name_vn"
                                        class="form-control @error('name_en') is-invalid @enderror" name="name_en"
                                        value="{{ old('name_en', $page->name_en ?? '') }}"
                                        placeholder="Enter your title page ">
                                    @error('name_en')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="intro-vn" class="form-label">Giới thiệu VN</label>
                                        <textarea id="intro-vn" class="form-control intro @error('intro_vn') is-invalid @enderror" name="intro_vn"
                                            rows="6" placeholder="Enter your message">{{ old('intro_vn', $page->intro_vn ?? '') }}</textarea>
                                        @error('intro_vn')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="intro-en" class="form-label">Giới thiệu EN</label>
                                        <textarea id="intro-en" class="form-control @error('intro_en') is-invalid @enderror" name="intro_en" rows="6"
                                            placeholder="Enter your message">{{ old('intro_en', $page->intro_en ?? '') }}</textarea>
                                        @error('intro_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                       
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="content-vn" class="form-label">Nội dung VN</label>
                                        <textarea id="content-vn" class="form-control @error('content_vn') is-invalid @enderror" name="content_vn"
                                            rows="6" placeholder="Enter your message">{{ old('content_vn', $page->content_vn ?? '') }}</textarea>
                                        @error('content_vn')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="content-en" class="form-label">Nội dung EN</label>
                                        <textarea id="content-en" class="form-control @error('content_en') is-invalid @enderror" name="content_en"
                                            rows="6" placeholder="Enter your message">{{ old('content_en', $page->content_en ?? '') }}</textarea>
                                        @error('content_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
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
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="firstNameinput" class="form-label">Hình ảnh(400x600)</label>
                                        <input type="file" id="fileInput" name="image"
                                            class="form-control @error('image') is-invalid @enderror">
                                        <div id="imageContainer"></div>
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                @if (!empty($page->image))
                                    <div class="col-md-6">
                                        <div class="mb-3" style="display:flex;flex-direction: column;">
                                            <label for="firstNameinput" class="form-label">Hình ảnh hiện tại</label>
                                            <img src="{{ asset('images/' . $imageFolder . '/' . $page->image) }}"
                                                alt="" width="200px" height="auto">
                                        </div>

                                    </div>
                                @endif
                                <div class="col-md-12">
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

                                                <div class="row">
                                                    @foreach (json_decode($page->image_detail) as $index => $img_detail)
                                                        <div class="col-md-3 mb-2">
                                                            <div class="position-relative">
                                                                <img src="{{ asset('images/' . $imageFolder . '/' . $img_detail) }}"
                                                                    alt="Product detail"
                                                                    style="width: 200px; height: 200px;object-fit: contain"
                                                                    class="img-fluid rounded">
                                                                <form class="position-absolute"
                                                                    style="top: 5px; right: 5px;"
                                                                    data-uuid="{{ $page->uuid }}"
                                                                    data-index="{{ $index }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm delete-image">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
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
                                <h5 class="card-title mb-0">Hiện thị</h5>
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
                                    <label for="choices-publish-status-input" class="form-label">Nội bật</label>

                                    <select class="form-select" name="hot">
                                        <option value="0"
                                            {{ (old('hot') ?: $page->hot ?? '') == 0 ? 'selected' : '' }}>
                                            Ẩn</option>
                                        <option value="1"
                                            {{ (old('hot') ?: $page->hot ?? '') == 1 ? 'selected' : '' }}>
                                            Bật</option>

                                    </select>
                                </div>

                                <div>
                                    <label for="choices-publish-visibility-input" class="form-label">STT</label>
                                    <input type="number" name="stt"
                                        class="form-control @error('stt') is-invalid @enderror"
                                        placeholder="Enter your stt" value="{{ old('stt', $page->stt ?? '') }}">
                                    @error('stt')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" name="slug"
                                        class="form-control @error('slug') is-invalid @enderror"
                                        placeholder="Enter your slug" value="{{ old('slug', $page->slug ?? '') }}">
                                    @error('slug')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">SEO</h5>
                            </div>
                            <div class="card-body">
                                <div>
                                    <label class="form-label" for="meta-description-input">Từ khóa</label>
                                    <textarea class="form-control @error('keywords') is-invalid @enderror" name="keywords" rows="3"
                                        placeholder="Enter your message">{{ old('keywords', $page->keywords ?? '') }}</textarea>
                                    @error('keywords')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label" for="meta-description-input">Mô tả ngắn</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3"
                                        placeholder="Enter your message">{{ old('description', $page->description ?? '') }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
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

    {{-- deleta image detail --}}
    <script>
        document.querySelectorAll('.delete-image').forEach(button => {
            button.addEventListener('click', function() {
            const form = this.closest('form');
                const uuid = form.getAttribute('data-uuid');
                const index = form.getAttribute('data-index');

                if (confirm('Bạn có chắc chắn muốn xóa hình ảnh này?')) {
                    fetch(`/admin/product/${uuid}/delete-image/${index}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                        })
                        .then(response => {
                            if (response.ok) {
                                // Remove the image from the DOM or refresh the image list
                                form.closest('.col-md-3').remove();
                            } else {
                                alert('Error deleting image.');
                            }
                        });
                }
            });
        });
    </script>
    @include('admin.partials.ckeditor')
@endsection
