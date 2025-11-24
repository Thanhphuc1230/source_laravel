@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Add')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}
                                {{ $nameItem }}</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="live-preview">
                                <form
                                    action="{{ route('admin.' . $nameClass . ($action == 'create' ? '.store' : '.update'), ['uuid' => $page->uuid ?? '']) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @if ($action == 'edit')
                                        <input type="hidden" name="currentPage" value="{{ $currentPage }}">
                                    @endif
                                    <div class="row">
                                        <div class="mb-3">
                                            <label class="form-label" for="product-title-input">Tiêu đề
                                            </label>
                                            <input type="text" id="name_vn" class="form-control @error('name_vn') is-invalid @enderror" name="name_vn"
                                                value="{{ old('name_vn', $page->name_vn ?? '') }}"
                                                placeholder="Enter your title page ">
                                            @error('name_vn')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="slug" class="form-label">Slug</label>
                                                <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                                    value="{{ old('slug', $page->slug ?? '') }}" placeholder="Slug">
                                            </div>
                                            @error('slug')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <!-- Preview Link Section -->
                                        @if(isset($page) && $page->slug && $page->status)
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Xem trang</label>
                                                <div>
                                                    <a href="{{ route('web.resolve', ['id' => $page->id_cate_new, 'slug' => $page->slug]) }}" target="_blank" class="text-decoration-none">
                                                        <span>{{ request()->getSchemeAndHttpHost() }}/{{ $page->id_cate_new }}-{{ $page->slug }}.html</span>
                                                        <i class="ri-eye-line ms-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <!--end col-->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="lastNameinput" class="form-label">Từ khóa</label>
                                                <textarea class="form-control @error('keywords') is-invalid @enderror" name="keywords" rows="3" placeholder="Enter your message">{{ old('keywords', $page->keywords ?? '') }}</textarea>
                                            </div>
                                            @error('keywords')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <!--end col-->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="compnayNameinput" class="form-label">Mô tả ngắn</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="Enter your message">{{ old('description', $page->description ?? '') }}</textarea>
                                            </div>
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="compnayNameinput" class="form-label">Số thứ tự</label>
                                                <input type="number" name="stt" class="form-control @error('stt') is-invalid @enderror"
                                                    placeholder="Enter your title page"
                                                    value="{{ old('stt', $page->stt ?? '') }}">
                                            </div>
                                            @error('stt')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="created_at" class="form-label">Ngày đăng</label>
                                                <input type="datetime-local" id="created_at" name="created_at"
                                                    class="form-control @error('created_at') is-invalid @enderror"
                                                    value="{{ old('created_at', isset($page->created_at) ? \Carbon\Carbon::parse($page->created_at)->format('Y-m-d\TH:i') : '') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Hình ảnh</label>
                                                <input type="file" id="fileInput" name="image" class="form-control @error('image') is-invalid @enderror">
                                                <div id="imageContainer"></div>
                                                @error('image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        @if (!empty($page->image))
                                            <div class="col-md-6">
                                                <div class="mb-3" style="display:flex;flex-direction: column;">
                                                    <label for="firstNameinput" class="form-label">Hình ảnh hiện
                                                        tại</label>
                                                    <img src="{{ asset('images/' . $imageFolder . '/' . $page->image) }}"
                                                        alt="" width="200px" height="auto">
                                                </div>
                                            </div>
                                        @endif
                                        <!--end col-->
                                        @if ($action == 'create')
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <input type="submit" name="return_back" class="btn btn-primary"
                                                        value="Lưu và tạo mới">
                                                    <input type="submit" name="return_list" class="btn btn-primary"
                                                        value="Lưu và về danh sách">
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <input type="submit" name="return_list" class="btn btn-primary"
                                                        value="Cập nhật">
                                                </div>
                                            </div>
                                        @endif
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Sử dụng hàm preview image đã định nghĩa trong master
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo preview image
        previewImage('fileInput', 'imageContainer');
    });
</script>
@endpush