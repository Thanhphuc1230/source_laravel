@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Add')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            @include('admin.partials.error')
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}
                                {{ $nameItem }}</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{ route('admin.'.$nameClass.($action == 'create' ? '.store' : '.update'), $action == 'edit' ? $page->id : []) }}" method="POST" enctype="multipart/form-data">

                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="type" class="form-label">Loại Font <span class="text-danger">*</span></label>
                                                <select name="type" id="fontType" class="form-select @error('type') is-invalid @enderror" required>
                                                    <option value="">-- Chọn loại --</option>
                                                    <option value="system" {{ (old('type') ?: $page->type ?? '') == 'system' ? 'selected' : '' }}>System Font</option>
                                                    <option value="google" {{ (old('type') ?: $page->type ?? '') == 'google' ? 'selected' : '' }}>Google Font</option>
                                                    <option value="upload" {{ (old('type') ?: $page->type ?? '') == 'upload' ? 'selected' : '' }}>Upload Font</option>
                                                </select>
                                                @error('type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Tên Font <span class="text-danger">*</span></label>
                                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                                                    placeholder="VD: Arial, Roboto"
                                                    value="{{ old('name', $page->name ?? '') }}" required>
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="family" class="form-label">Font Family CSS <span class="text-danger">*</span></label>
                                                <input type="text" id="family" name="family" class="form-control @error('family') is-invalid @enderror"
                                                    placeholder="VD: 'Arial', sans-serif"
                                                    value="{{ old('family', $page->family ?? '') }}" required>
                                                <small class="form-text text-muted">Font family sử dụng trong CSS</small>
                                                @error('family')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Google Font URL -->
                                        <div class="col-md-12" id="googleUrlField" style="display: none;">
                                            <div class="mb-3">
                                                <label for="css_url" class="form-label">Google Font URL <span class="text-danger">*</span></label>
                                                <input type="url" id="css_url" name="css_url" class="form-control @error('css_url') is-invalid @enderror"
                                                    placeholder="https://fonts.googleapis.com/css2?family=Roboto"
                                                    value="{{ old('css_url', $page->css_url ?? '') }}">
                                                <small class="form-text text-muted">Lấy từ Google Fonts</small>
                                                @error('css_url')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Upload Font File -->
                                        <div class="col-md-12" id="uploadFileField" style="display: none;">
                                            <div class="mb-3">
                                                <label for="font_file" class="form-label">File Font <span class="text-danger">*</span></label>
                                                <input type="file" id="font_file" name="font_file" class="form-control @error('font_file') is-invalid @enderror"
                                                    accept=".woff,.woff2,.ttf,.otf">
                                                <small class="form-text text-muted">Hỗ trợ: .woff, .woff2, .ttf, .otf (Max: 5MB)</small>
                                                @error('font_file')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sort_order" class="form-label">Thứ tự hiển thị</label>
                                                <input type="number" id="sort_order" name="sort_order" class="form-control"
                                                    value="{{ old('sort_order', $page->sort_order ?? 0) }}" min="0">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="is_active" class="form-label">Trạng thái</label>
                                                <select class="form-select" name="is_active">
                                                    <option value="1" {{ (old('is_active') ?: $page->is_active ?? 1) == 1 ? 'selected' : '' }}>Kích hoạt</option>
                                                    <option value="0" {{ (old('is_active') ?: $page->is_active ?? 1) == 0 ? 'selected' : '' }}>Vô hiệu hóa</option>
                                                </select>
                                            </div>
                                        </div>

                                        @if($action == 'create')
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <input type="submit" name="return_back" class="btn btn-primary" value="Lưu và tạo mới">
                                                <input type="submit" name="return_list" class="btn btn-primary" value="Lưu và về danh sách">
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <input type="submit" name="return_list" class="btn btn-primary" value="Cập nhật">
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
    <script>
        document.getElementById('fontType').addEventListener('change', function() {
            const type = this.value;
            const googleField = document.getElementById('googleUrlField');
            const uploadField = document.getElementById('uploadFileField');

            // Ẩn tất cả
            googleField.style.display = 'none';
            uploadField.style.display = 'none';

            // Hiển thị field tương ứng
            if (type === 'google') {
                googleField.style.display = 'block';
                googleField.querySelector('input').required = true;
                uploadField.querySelector('input').required = false;
            } else if (type === 'upload') {
                uploadField.style.display = 'block';
                uploadField.querySelector('input').required = true;
                googleField.querySelector('input').required = false;
            } else {
                googleField.querySelector('input').required = false;
                uploadField.querySelector('input').required = false;
            }
        });

        // Trigger change event on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('fontType').dispatchEvent(new Event('change'));
        });
    </script>
@endsection