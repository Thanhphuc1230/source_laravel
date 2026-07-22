@props([
    'locale',
    'model' => $page ?? null,
    'imageFolder' => '',
    'base' => 'image',
    'label' => 'Hình ảnh',
    'dimensions' => '400x600'
])

@php
    // Đọc active languages từ global systemConfig
    $activeLocales = isset($systemConfig) ? ($systemConfig->active_languages ?? ['vi', 'en']) : ['vi', 'en'];
    
    $shouldRender = false;
    if ($locale === 'vn' && in_array('vi', $activeLocales)) {
        $shouldRender = true;
    }
    if ($locale === 'en' && in_array('en', $activeLocales)) {
        $shouldRender = true;
    }
@endphp

@if ($shouldRender)
@php
    $fieldName = $base . '_' . $locale;
    $currentImage = data_get($model, $fieldName);
    $inputUuid = 'file_' . $fieldName;
    $containerUuid = 'container_' . $fieldName;
@endphp

<div class="col-md-6">
    <div class="mb-3">
        <label for="{{ $inputUuid }}" class="form-label">{{ $label }} {{ strtoupper($locale) }} ({{ $dimensions }})</label>
        <input type="file" id="{{ $inputUuid }}" name="{{ $fieldName }}" 
               class="form-control @error($fieldName) is-invalid @enderror">
        <div id="{{ $containerUuid }}"></div>
        @error($fieldName)
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    @if (!empty($currentImage))
        <div class="mb-3" style="display:flex;flex-direction: column;">
            <label class="form-label">{{ $label }} {{ strtoupper($locale) }} hiện tại</label>
            <img src="{{ $currentImage }}" 
                  alt="" width="250px" height="300" style="object-fit: contain;">
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof previewImage === 'function') {
            previewImage('{{ $inputUuid }}', '{{ $containerUuid }}');
        }
    });
</script>
@endpush
@endif
