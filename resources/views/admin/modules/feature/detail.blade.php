@extends('admin.master')
@section('module', $nameItem)
@section('action', $action == 'create' ? 'Thêm' : 'Chỉnh sửa')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} {{ $nameItem }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Trang</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.' . $nameClass . ($action == 'create' ? '.store' : '.update'), ['uuid' => $page->uuid ?? '']) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                @include('admin.partials.localized-fields', [
                                    'model' => $page ?? null,
                                    'fields' => [
                                        ['base' => 'title', 'label' => 'Tiêu đề tính năng', 'type' => 'text', 'col' => 'col-md-12'],
                                        ['base' => 'content', 'label' => 'Nội dung mô tả', 'type' => 'textarea', 'col' => 'col-md-12', 'rows' => 4]
                                    ]
                                ])
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold">Hình ảnh đại diện (Icon/SVG)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="image" class="form-label">Chọn hình ảnh</label>
                                        <input type="file" id="image" name="image" class="form-control mb-2 @error('image') is-invalid @enderror">
                                        @error('image')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    @if (!empty($page->image))
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Hình ảnh hiện tại</label>
                                            <div class="p-2 border rounded bg-light d-inline-block">
                                                <img src="{{ $page->image }}" alt="current image" style="max-height: 80px; max-width: 120px; object-fit: contain;">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-end mb-3">
                            @if ($action == 'create')
                                <input type="submit" name="return_back" class="btn btn-primary px-4 py-2" value="Lưu và tạo mới">
                                <input type="submit" name="return_list" class="btn btn-primary px-4 py-2" value="Lưu và về danh sách">
                            @else
                                <input type="submit" name="return_list" class="btn btn-primary px-4 py-2" value="Cập nhật">
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold">Cấu hình chung</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="stt" class="form-label">Số thứ tự hiển thị</label>
                                    <input type="number" id="stt" name="stt" class="form-control"
                                        placeholder="Nhập số thứ tự" value="{{ old('stt', $page->stt ?? '0') }}">
                                    @error('stt')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <div class="mb-3">
                                    <label for="created_at" class="form-label">Ngày đăng</label>
                                    <input type="datetime-local" id="created_at" name="created_at" class="form-control"
                                        value="{{ old('created_at', isset($page->created_at) ? \Carbon\Carbon::parse($page->created_at)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
