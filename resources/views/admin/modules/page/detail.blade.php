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
                                <li class="breadcrumb-item"><a>Trang</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} trang
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
                                {{-- <div class="mb-3">
                                    <label class="form-label" for="product-title-input">Tiêu đề EN</label>
                                    <input type="text" id="name_vn"
                                        class="form-control @error('name_en') is-invalid @enderror" name="name_en"
                                        value="{{ old('name_en', $page->name_en ?? '') }}"
                                        placeholder="Enter your title page ">
                                    @error('name_en')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> --}}
                       
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
                                {{-- <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="content-en" class="form-label">Nội dung EN</label>
                                        <textarea id="content-en" class="form-control @error('content_en') is-invalid @enderror" name="content_en"
                                            rows="6" placeholder="Enter your message">{{ old('content_en', $page->content_en ?? '') }}</textarea>
                                        @error('content_en')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <!-- end card -->
                       
                        <div class="card">
                            <div class="card-body">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="firstNameinput" class="form-label">Hình ảnh</label>
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
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="created_at" class="form-label">Ngày đăng</label>
                                    <input type="datetime-local" id="created_at" name="created_at"
                                        class="form-control @error('created_at') is-invalid @enderror"
                                        value="{{ old('created_at', isset($page->created_at) ? \Carbon\Carbon::parse($page->created_at)->format('Y-m-d\TH:i') : '') }}">
                                </div>

                                <div class="mb-3">
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
                                    <input type="text" name="slug" id="slug-input"
                                        class="form-control @error('slug') is-invalid @enderror"
                                        placeholder="Enter your slug" value="{{ old('slug', $page->slug ?? '') }}">
                                    @error('slug')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Preview Link Section -->
                                @if(isset($page) && $page->slug && $page->status)
                                <div class="mt-3">
                                    <label class="form-label">Xem trang</label>
                                    <div>
                                        <a href="{{ route('web.resolve', ['slug' => $page->slug]) }}" target="_blank" class="text-decoration-none">
                                            <span>{{ request()->getSchemeAndHttpHost() }}/{{ $page->slug }}.html</span>
                                            <i class="ri-eye-line ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                                @endif


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

                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </form>

        </div>
        <!-- container-fluid -->
    </div>
    @include('admin.partials.ckeditor')
@endsection
