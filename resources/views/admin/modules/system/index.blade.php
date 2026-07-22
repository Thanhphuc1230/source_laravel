@extends('admin.master')
@section('module', 'Cấu hình')
@section('action', 'Thông tin hệ thống')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Cấu hình hệ thống</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>Hệ thống</a></li>
                                <li class="breadcrumb-item active">Thông tin website</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.system.update', ['id' => $system->id_system]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Cấu hình thông tin Website -->
                    <div class="col-xxl-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold">Thông tin website</h5>
                            </div>
                            <div class="card-body">
                                @include('admin.partials.localized-fields', [
                                    'model' => $system,
                                    'fields' => [
                                        ['base' => 'name', 'label' => 'Tên công ty', 'type' => 'text', 'col' => 'col-md-6'],
                                        ['base' => 'address', 'label' => 'Địa chỉ', 'type' => 'text', 'col' => 'col-md-6'],
                                    ]
                                ])

                                <div class="row">
                                    @foreach(['phone' => 'Số điện thoại', 'email' => 'Email', 'email_alert' => 'Email nhận thông báo'] as $key => $label)
                                        <div class="col-md-4 mb-3">
                                            <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                                            <input type="text" id="{{ $key }}" name="{{ $key }}" class="form-control"
                                                placeholder="{{ $label }}" value="{{ old($key, $system->$key) }}">
                                            @error($key)<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    @endforeach
                                </div>

                                @include('admin.partials.localized-fields', [
                                    'model' => $system,
                                    'fields' => [
                                        ['base' => 'footer', 'label' => 'Footer content', 'type' => 'textarea', 'col' => 'col-md-6', 'rows' => 5, 'ckeditor' => true]
                                    ]
                                ])
                            </div>
                        </div>
                    </div>

                    <!-- Cấu hình Mạng xã hội -->
                    <div class="col-xxl-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold">Mạng xã hội</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach(['facebook' => 'Facebook', 'twitter' => 'Twitter/X', 'youtube' => 'Youtube', 'instagram' => 'Instagram', 'zalo' => 'Zalo'] as $key => $label)
                                        <div class="col-md-4 mb-3">
                                            <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                                            <input type="text" id="{{ $key }}" name="{{ $key }}" class="form-control"
                                                placeholder="Đường dẫn {{ $label }}" value="{{ old($key, $system->$key) }}">
                                            @error($key)<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cấu hình Hình ảnh (Logo & Favicon) -->
                    <div class="col-xxl-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold">Hình ảnh hệ thống</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach([
                                        'logo' => ['label' => 'Logo (200x100)', 'width' => '150px'],
                                        'favicon' => ['label' => 'Favicon', 'width' => '48px']
                                    ] as $key => $config)
                                        <div class="col-md-6 mb-3">
                                            <label for="{{ $key }}" class="form-label">{{ $config['label'] }}</label>
                                            <input type="file" id="{{ $key }}" name="{{ $key }}" class="form-control mb-2">
                                            @if($system->$key)
                                                <div class="mt-2 p-2 border rounded bg-light d-inline-block">
                                                    <img src="{{ $system->$key }}" alt="{{ $key }}" style="max-height: 80px; max-width: {{ $config['width'] }}; object-fit: contain;">
                                                </div>
                                            @endif
                                            @error($key)<span class="text-danger">{{ $message }}</span>@enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cấu hình SEO & Google Map -->
                    <div class="col-xxl-12">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="card-title mb-0 font-weight-bold">Cấu hình SEO & Bản đồ</h5>
                            </div>
                            <div class="card-body">
                                @include('admin.partials.localized-fields', [
                                    'model' => $system,
                                    'fields' => [
                                        ['base' => 'keyword', 'label' => 'Từ khóa SEO', 'type' => 'textarea', 'rows' => 3],
                                        ['base' => 'description', 'label' => 'Mô tả SEO', 'type' => 'textarea', 'rows' => 3],
                                    ]
                                ])

                                <div class="mb-3">
                                    <label for="map" class="form-label">Iframe Google Map</label>
                                    <textarea class="form-control" id="map" name="map" rows="3" placeholder="Nhập mã nhúng Iframe Google Map">{{ old('map', $system->map) }}</textarea>
                                    @error('map')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                                        <i class="ri-save-line align-bottom me-1"></i> Lưu cấu hình
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.partials.ckeditor')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof CKEDITOR !== 'undefined') {
                if (document.getElementById('footer-vn')) {
                    CKEDITOR.replace('footer-vn', options);
                }
                if (document.getElementById('footer-en')) {
                    CKEDITOR.replace('footer-en', options);
                }
            }
        });
    </script>
@endsection
