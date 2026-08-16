@extends('admin.master')
@section('module', $nameItem)
@section('action', $action == 'create' ? 'Thêm' : 'Chỉnh sửa')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} {{ $nameItem }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Giới thiệu</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}</li>
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
                            <div class="card-header">
                                <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-vi"
                                            role="tab" aria-selected="true">
                                            <img src="{{ asset('uploads/icon/vietnam.png') }}" alt="vi" class="me-1 align-middle" style="width: 18px; height: 12px; object-fit: cover; border-radius: 2px; margin-top: -2px;">
                                            Tiếng Việt
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#tab-en"
                                            role="tab" aria-selected="false">
                                            <img src="{{ asset('uploads/icon/usa.png') }}" alt="en" class="me-1 align-middle" style="width: 18px; height: 12px; object-fit: cover; border-radius: 2px; margin-top: -2px;">
                                            Tiếng Anh (EN)
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- end card header -->

                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- Tiếng Việt tab -->
                                    <div class="tab-pane active" id="tab-vi" role="tabpanel">
                                        <div class="mb-3">
                                            <label for="name_vn" class="form-label">Tiêu đề (VN) <span class="text-danger">*</span></label>
                                            <input type="text" id="name_vn" name="name_vn" 
                                                class="form-control @error('name_vn') is-invalid @enderror" 
                                                value="{{ old('name_vn', $page->name_vn ?? '') }}" 
                                                placeholder="Nhập tiêu đề giới thiệu">
                                            @error('name_vn')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="intro_vn" class="form-label">Mô tả ngắn (VN)</label>
                                            <textarea id="intro_vn" name="intro_vn" rows="3" 
                                                class="form-control @error('intro_vn') is-invalid @enderror" 
                                                placeholder="Nhập mô tả ngắn">{{ old('intro_vn', $page->intro_vn ?? '') }}</textarea>
                                            @error('intro_vn')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="content-vn" class="form-label">Nội dung chi tiết (VN) <span class="text-danger">*</span></label>
                                            <textarea id="content-vn" name="content_vn" data-ckeditor="true"
                                                class="form-control @error('content_vn') is-invalid @enderror">{{ old('content_vn', $page->content_vn ?? '') }}</textarea>
                                            @error('content_vn')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- end tab-pane -->

                                    <!-- Tiếng Anh tab -->
                                    <div class="tab-pane" id="tab-en" role="tabpanel">
                                        <div class="mb-3">
                                            <label for="name_en" class="form-label">Tiêu đề (EN)</label>
                                            <input type="text" id="name_en" name="name_en" 
                                                class="form-control @error('name_en') is-invalid @enderror" 
                                                value="{{ old('name_en', $page->name_en ?? '') }}" 
                                                placeholder="Enter title">
                                            @error('name_en')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="intro_en" class="form-label">Mô tả ngắn (EN)</label>
                                            <textarea id="intro_en" name="intro_en" rows="3" 
                                                class="form-control @error('intro_en') is-invalid @enderror" 
                                                placeholder="Enter description">{{ old('intro_en', $page->intro_en ?? '') }}</textarea>
                                            @error('intro_en')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="content-en" class="form-label">Nội dung chi tiết (EN)</label>
                                            <textarea id="content-en" name="content_en" data-ckeditor="true"
                                                class="form-control @error('content_en') is-invalid @enderror">{{ old('content_en', $page->content_en ?? '') }}</textarea>
                                            @error('content_en')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- end tab-pane -->
                                </div>
                                <!-- end tab content -->
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <!-- Card 2: Stats Configuration (Dynamic JSON) -->
                        <div class="card mt-4">
                            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                                <h5 class="card-title mb-0">Chỉ số thống kê</h5>
                                <button type="button" id="add-stat-btn" class="btn btn-sm btn-primary">
                                    <i class="ri-add-line align-bottom me-1"></i> Thêm chỉ số
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="stats-container" class="row">
                                    @php
                                        $stats = old('stats', $page->stats ?? []);
                                    @endphp
                                    @forelse($stats as $index => $stat)
                                        <div class="col-md-6 stat-item mb-3" data-index="{{ $index }}">
                                            <div class="border rounded p-3 position-relative bg-light">
                                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-stat-btn" aria-label="Close" style="font-size: 0.8rem;"></button>
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <label class="form-label text-xs mb-1">Ảnh Icon (PNG, SVG, JPG)</label>
                                                        <input type="file" name="stats_files[{{ $index }}][icon]" class="form-control form-control-sm stats-file-input">
                                                        <input type="hidden" name="stats[{{ $index }}][icon]" class="stats-icon-hidden" value="{{ $stat['icon'] ?? '' }}">
                                                        @if(!empty($stat['icon']))
                                                            <div class="mt-1 d-flex align-items-center gap-1">
                                                                <span class="text-xs text-muted">Icon hiện tại:</span>
                                                                <img src="{{ asset($stat['icon']) }}" alt="Icon" style="width: 24px; height: 24px; object-fit: contain; border: 1px solid #eee; padding: 2px;">
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-xs mb-1">Giá trị <span class="text-danger">*</span></label>
                                                        <input type="text" name="stats[{{ $index }}][value]" class="form-control form-control-sm" value="{{ $stat['value'] ?? '' }}" required placeholder="Ví dụ: 10+">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-xs mb-1">Tên Tiếng Việt <span class="text-danger">*</span></label>
                                                        <input type="text" name="stats[{{ $index }}][name_vn]" class="form-control form-control-sm" value="{{ $stat['name_vn'] ?? '' }}" required placeholder="Nhập tên tiếng Việt">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label text-xs mb-1">Tên Tiếng Anh</label>
                                                        <input type="text" name="stats[{{ $index }}][name_en]" class="form-control form-control-sm" value="{{ $stat['name_en'] ?? '' }}" placeholder="Nhập tên tiếng Anh">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div id="no-stats-alert" class="col-12 text-center text-muted py-4">
                                            Chưa có chỉ số thống kê nào. Nhấp "Thêm chỉ số" để cấu hình.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-end mt-4 mb-3">
                            <a href="{{ route('admin.' . $nameClass . '.index') }}" class="btn btn-light me-2">Hủy bỏ</a>
                            <input type="submit" name="return_list" class="btn btn-success" value="Cập nhật">
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-lg-4">
                        <!-- Publish Config -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Hiển thị</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <div class="form-check form-switch form-switch-success">
                                        <input class="form-check-input" type="checkbox" role="switch" name="status" value="1"
                                            {{ (old('status') ?? ($page->status ?? 1)) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label">Kích hoạt hiển thị</label>
                                    </div>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="stt" class="form-label">Thứ tự sắp xếp (STT)</label>
                                    <input type="number" id="stt" name="stt" class="form-control" 
                                        value="{{ old('stt', $page->stt ?? 1) }}">
                                    @error('stt')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Image Config -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Ảnh đại diện</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Tệp ảnh</label>
                                    <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3" id="image_preview">
                                    @if (!empty($page->image))
                                        <label class="form-label d-block">Ảnh hiện tại</label>
                                        <img src="{{ $page->image }}" alt="Preview" 
                                             style="max-width: 100%; border: 1px solid #ddd; padding: 5px; border-radius: 4px; object-fit: contain;">
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="link" class="form-label">Liên kết khi click vào ảnh (Link)</label>
                                    <input type="text" id="link" name="link" class="form-control @error('link') is-invalid @enderror" 
                                        value="{{ old('link', $page->link ?? '') }}" placeholder="Nhập link liên kết (ví dụ: /lien-he.html)">
                                    @error('link')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </form>
        </div>
        <!-- container-fluid -->
    </div>
    @include('admin.partials.ckeditor')
    
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof previewImage === 'function') {
                    previewImage('image', 'image_preview');
                }

                const statsContainer = document.getElementById('stats-container');
                const addStatBtn = document.getElementById('add-stat-btn');
                let statIndex = {{ count(old('stats', $page->stats ?? [])) }};

                function updateNoStatsAlert() {
                    const alert = document.getElementById('no-stats-alert');
                    const items = statsContainer.querySelectorAll('.stat-item');
                    if (items.length === 0) {
                        if (!alert) {
                            statsContainer.innerHTML = `
                                <div id="no-stats-alert" class="col-12 text-center text-muted py-4">
                                    Chưa có chỉ số thống kê nào. Nhấp "Thêm chỉ số" để cấu hình.
                                </div>
                            `;
                        }
                    } else {
                        if (alert) {
                            alert.remove();
                        }
                    }
                }

                addStatBtn.addEventListener('click', function() {
                    const alert = document.getElementById('no-stats-alert');
                    if (alert) alert.remove();

                    const html = `
                        <div class="col-md-6 stat-item mb-3" data-index="${statIndex}">
                            <div class="border rounded p-3 position-relative bg-light">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-stat-btn" aria-label="Close" style="font-size: 0.8rem;"></button>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label text-xs mb-1">Ảnh Icon (PNG, SVG, JPG) <span class="text-danger">*</span></label>
                                        <input type="file" name="stats_files[${statIndex}][icon]" class="form-control form-control-sm stats-file-input" required>
                                        <input type="hidden" name="stats[${statIndex}][icon]" class="stats-icon-hidden" value="">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-xs mb-1">Giá trị <span class="text-danger">*</span></label>
                                        <input type="text" name="stats[${statIndex}][value]" class="form-control form-control-sm" required placeholder="Ví dụ: 10+">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-xs mb-1">Tên Tiếng Việt <span class="text-danger">*</span></label>
                                        <input type="text" name="stats[${statIndex}][name_vn]" class="form-control form-control-sm" required placeholder="Nhập tên tiếng Việt">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-xs mb-1">Tên Tiếng Anh</label>
                                        <input type="text" name="stats[${statIndex}][name_en]" class="form-control form-control-sm" placeholder="Nhập tên tiếng Anh">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    statsContainer.insertAdjacentHTML('beforeend', html);
                    statIndex++;
                });

                statsContainer.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-stat-btn') || e.target.closest('.remove-stat-btn')) {
                        const item = e.target.closest('.stat-item');
                        if (item) {
                            item.remove();
                            updateNoStatsAlert();
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
