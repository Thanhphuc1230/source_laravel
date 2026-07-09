@extends('admin.master')
@section('module', 'Cài đặt Trade Partner')
@section('action', 'Cập nhật')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Cài đặt Trade Partner Section</h4>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.site_setting.updateSettings') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Trade Partner Section -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Trade Partner Section</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tiêu đề chính</label>
                                    <input type="text" name="settings[trade_partner_title]" class="form-control" 
                                           value="{{ $settings['trade_partner_title'] ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phụ đề</label>
                                    <input type="text" name="settings[trade_partner_subtitle]" class="form-control" 
                                           value="{{ $settings['trade_partner_subtitle'] ?? '' }}">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Text Button</label>
                                    <input type="text" name="settings[trade_partner_button_text]" class="form-control" 
                                           value="{{ $settings['trade_partner_button_text'] ?? '' }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Link Button</label>
                                    <input type="text" name="settings[trade_partner_button_link]" class="form-control" 
                                           value="{{ $settings['trade_partner_button_link'] ?? '' }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Background Color (optional)</label>
                                    <input type="color" name="settings[trade_partner_background_color]" class="form-control form-control-color" 
                                           value="{{ $settings['trade_partner_background_color'] ?? '#0f766e' }}">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Label</label>
                                    <input type="text" name="settings[trade_partner_phone_label]" class="form-control" 
                                           value="{{ $settings['trade_partner_phone_label'] ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="settings[trade_partner_phone_number]" class="form-control" 
                                           value="{{ $settings['trade_partner_phone_number'] ?? '' }}">
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Hình ảnh Trade Partner</label>
                                    <input type="file" name="trade_partner_image" class="form-control" id="tradePartnerImageInput" accept="image/*">
                                    <input type="hidden" name="settings[trade_partner_image]" id="tradePartnerImagePath" 
                                           value="{{ $settings['trade_partner_image'] ?? '' }}">
                                    <div class="mt-2" id="tradePartnerImagePreview">
                                        @if(isset($settings['trade_partner_image']) && $settings['trade_partner_image'])
                                            <img src="{{ str_starts_with($settings['trade_partner_image'], 'images/') ? asset($settings['trade_partner_image']) : asset('images/site_setting/' . $settings['trade_partner_image']) }}" 
                                                 alt="Trade Partner Image" class="img-thumbnail" style="max-width: 400px; max-height: 300px;">
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
                            <h5 class="card-title mb-0">Features / Lợi ích</h5>
                            <button type="button" class="btn btn-sm btn-success" id="addFeature">
                                <i class="ri-add-line"></i> Thêm Feature
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="featuresContainer">
                                @php
                                    $features = $settings['trade_partner_features'] ?? [];
                                    if (is_string($features)) {
                                        $features = json_decode($features, true) ?? [];
                                    }
                                @endphp
                                @foreach($features as $index => $feature)
                                <div class="feature-item card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <label class="form-label">Tiêu đề</label>
                                                <input type="text" name="settings[trade_partner_features][{{ $index }}][title]" 
                                                       class="form-control" value="{{ $feature['title'] ?? '' }}">
                                            </div>
                                            <div class="col-md-7 mb-2">
                                                <label class="form-label">Mô tả</label>
                                                <input type="text" name="settings[trade_partner_features][{{ $index }}][description]" 
                                                       class="form-control" value="{{ $feature['description'] ?? '' }}">
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

    // Preview trade partner image
    document.getElementById('tradePartnerImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('tradePartnerImagePreview').innerHTML = 
                    `<img src="${e.target.result}" alt="Trade Partner Image" class="img-thumbnail" style="max-width: 400px; max-height: 300px;">`;
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
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" name="settings[trade_partner_features][${featureIndex}][title]" 
                                   class="form-control">
                        </div>
                        <div class="col-md-7 mb-2">
                            <label class="form-label">Mô tả</label>
                            <input type="text" name="settings[trade_partner_features][${featureIndex}][description]" 
                                   class="form-control">
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
