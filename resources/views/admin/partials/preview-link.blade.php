@props([
    'model' => $page ?? null,
    'col' => 'col-md-12 mb-3'
])

@php
    $slugVal = data_get($model, 'slug_vn') ?: data_get($model, 'slug');
@endphp

@if(isset($model) && $slugVal && data_get($model, 'status'))
    <div class="{{ $col }}">
        <label class="form-label">Xem trang</label>
        <div>
            <a href="{{ route('web.resolve', ['slug' => $slugVal]) }}" target="_blank" class="text-decoration-none">
                <span>{{ request()->getSchemeAndHttpHost() }}/{{ $slugVal }}.html</span>
                <i class="ri-eye-line ms-1"></i>
            </a>
        </div>
    </div>
@endif
