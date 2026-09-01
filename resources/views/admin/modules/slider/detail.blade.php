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
                                        ['base' => 'name', 'label' => 'Tiêu đề Slider', 'type' => 'text', 'col' => 'col-md-12']
                                    ]
                                ])
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold">Hình ảnh Slider (Desktop)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($adminLanguages as $locale)
                                        @include('admin.partials.image-upload', [
                                            'locale' => $locale,
                                            'imageFolder' => $imageFolder ?? 'slider',
                                            'model' => $page ?? null,
                                            'base' => 'image_desktop',
                                            'label' => 'Banner Desktop',
                                            'dimensions' => '1920x800'
                                        ])
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold">Hình ảnh Slider (Mobile - Tùy chọn)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($adminLanguages as $locale)
                                        @include('admin.partials.image-upload', [
                                            'locale' => $locale,
                                            'imageFolder' => $imageFolder ?? 'slider',
                                            'model' => $page ?? null,
                                            'base' => 'image_mobile',
                                            'label' => 'Banner Mobile',
                                            'dimensions' => '600x600'
                                        ])
                                    @endforeach
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
                                    <label for="link" class="form-label">Đường dẫn chuyển trang (Link)</label>
                                    <input type="text" id="link" name="link" class="form-control"
                                        placeholder="Nhập link chuyển trang" value="{{ old('link', $page->link ?? '') }}">
                                    @error('link')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>

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
