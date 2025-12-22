@extends('admin.master')
@section('module', 'Cài đặt Homepage')
@section('action', 'Cập nhật')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Cài đặt Homepage</h4>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.site_setting.updateSettings') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Hero Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Hero Section</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tiêu đề chính</label>
                                    <input type="text" name="settings[hero_title]" class="form-control" value="{{ $settings['hero_title'] ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phụ đề</label>
                                    <input type="text" name="settings[hero_subtitle]" class="form-control" value="{{ $settings['hero_subtitle'] ?? '' }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="settings[hero_description]" class="form-control" rows="3">{{ $settings['hero_description'] ?? '' }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Text Button</label>
                                    <input type="text" name="settings[hero_button_text]" class="form-control" value="{{ $settings['hero_button_text'] ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Link Button</label>
                                    <input type="text" name="settings[hero_button_link]" class="form-control" value="{{ $settings['hero_button_link'] ?? '' }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Hình ảnh Hero</label>
                                    <input type="file" name="hero_image" class="form-control" id="heroImageInput" accept="image/*">
                                    <input type="hidden" name="settings[hero_image]" id="heroImagePath" value="{{ $settings['hero_image'] ?? '' }}">
                                    <div class="mt-2" id="heroImagePreview">
                                        @if(isset($settings['hero_image']) && $settings['hero_image'])
                                            <img src="{{ asset('images/site_setting/' . $settings['hero_image']) }}" alt="Hero Image" class="img-thumbnail" style="max-width: 300px; max-height: 200px;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Features / Tính năng nổi bật</h5>
                            <button type="button" class="btn btn-sm btn-success" id="addFeature">
                                <i class="ri-add-line"></i> Thêm Feature
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="featuresContainer">
                                @php
                                    $features = $settings['features'] ?? [];
                                    if (is_string($features)) {
                                        $features = json_decode($features, true) ?? [];
                                    }
                                @endphp
                                @foreach($features as $index => $feature)
                                <div class="feature-item card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3 mb-2">
                                                <label class="form-label">Icon Class</label>
                                                <input type="text" name="features[{{ $index }}][icon]" class="form-control" value="{{ $feature['icon'] ?? '' }}" placeholder="ri-verified-badge-line">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">Tiêu đề</label>
                                                <input type="text" name="features[{{ $index }}][title]" class="form-control" value="{{ $feature['title'] ?? '' }}">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">Phụ đề (optional)</label>
                                                <input type="text" name="features[{{ $index }}][subtitle]" class="form-control" value="{{ $feature['subtitle'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1 mb-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-feature">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let featureIndex = {{ count($features) }};

    // Preview hero image
    document.getElementById('heroImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('heroImagePreview').innerHTML = 
                    `<img src="${e.target.result}" alt="Hero Image" class="img-thumbnail" style="max-width: 300px; max-height: 200px;">`;
            }
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('addFeature').addEventListener('click', function() {
        const container = document.getElementById('featuresContainer');
        const featureHTML = `
            <div class="feature-item card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Icon Class</label>
                            <input type="text" name="features[${featureIndex}][icon]" class="form-control" placeholder="ri-verified-badge-line">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" name="features[${featureIndex}][title]" class="form-control">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Phụ đề (optional)</label>
                            <input type="text" name="features[${featureIndex}][subtitle]" class="form-control">
                        </div>
                        <div class="col-md-1 mb-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-feature">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', featureHTML);
        featureIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            e.target.closest('.feature-item').remove();
        }
    });
</script>
@endpush
@endsection