@extends('admin.master')
@section('module', $title)
@section('action', $action == 'create' ? 'Thêm' : 'Chỉnh sửa')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}
                            {{ $title }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a>{{ $title }}</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} setting
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <form
                action="{{ route('admin.' . $module . ($action == 'create' ? '.store' : '.update'), ['uuid' => $setting->uuid ?? '']) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label" for="key">Setting Key <span class="text-danger">*</span></label>
                                    <input type="text" id="key"
                                        class="form-control @error('key') is-invalid @enderror" name="key"
                                        value="{{ old('key', $setting->key ?? '') }}"
                                        placeholder="Nhập key setting">
                                    @error('key')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type" class="form-label">Data Type <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type">
                                                <option value="">Chọn type</option>
                                                <option value="text" {{ old('type', $setting->type ?? '') == 'text' ? 'selected' : '' }}>Text</option>
                                                <option value="number" {{ old('type', $setting->type ?? '') == 'number' ? 'selected' : '' }}>Number</option>
                                                <option value="json" {{ old('type', $setting->type ?? '') == 'json' ? 'selected' : '' }}>JSON</option>
                                                <option value="html" {{ old('type', $setting->type ?? '') == 'html' ? 'selected' : '' }}>HTML</option>
                                                <option value="boolean" {{ old('type', $setting->type ?? '') == 'boolean' ? 'selected' : '' }}>Boolean</option>
                                            </select>
                                            @error('type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="group" class="form-label">Group <span class="text-danger">*</span></label>
                                            <select class="form-control @error('group') is-invalid @enderror" id="group" name="group">
                                                <option value="">Chọn group</option>
                                                <option value="display" {{ old('group', $setting->group ?? '') == 'display' ? 'selected' : '' }}>Display</option>
                                                <option value="policy" {{ old('group', $setting->group ?? '') == 'policy' ? 'selected' : '' }}>Policy</option>
                                                <option value="general" {{ old('group', $setting->group ?? '') == 'general' ? 'selected' : '' }}>General</option>
                                                <option value="performance" {{ old('group', $setting->group ?? '') == 'performance' ? 'selected' : '' }}>Performance</option>
                                            </select>
                                            @error('group')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="value" class="form-label">Value <span class="text-danger">*</span></label>

                                    <!-- Text Input -->
                                    <div id="text-input" class="value-input" style="display: none;">
                                        <input type="text" class="form-control @error('value') is-invalid @enderror"
                                               name="value" value="{{ old('value', $setting->value ?? '') }}" placeholder="Nhập giá trị text">
                                    </div>

                                    <!-- Number Input -->
                                    <div id="number-input" class="value-input" style="display: none;">
                                        <input type="number" class="form-control @error('value') is-invalid @enderror"
                                               name="value" value="{{ old('value', $setting->value ?? '') }}" placeholder="Nhập giá trị số">
                                    </div>

                                    <!-- Textarea for JSON/HTML -->
                                    <div id="textarea-input" class="value-input" style="display: none;">
                                        <textarea class="form-control @error('value') is-invalid @enderror"
                                                  name="value" rows="5" placeholder="Nhập JSON hoặc HTML content">{{ old('value', $setting->value ?? '') }}</textarea>
                                    </div>

                                    <!-- Boolean Select -->
                                    <div id="boolean-input" class="value-input" style="display: none;">
                                        <select class="form-control @error('value') is-invalid @enderror" name="value">
                                            <option value="true" {{ old('value', $setting->value ?? '') == 'true' ? 'selected' : '' }}>✅ Có / Bật (true)</option>
                                            <option value="false" {{ old('value', $setting->value ?? '') == 'false' ? 'selected' : '' }}>❌ Không / Tắt (false)</option>
                                        </select>
                                    </div>

                                    @error('value')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="3"
                                              placeholder="Mô tả chức năng của setting">{{ old('description', $setting->description ?? '') }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <!-- end card -->

                        <div class="text-end mb-3">
                            <button type="submit" class="btn btn-success w-sm">Lưu</button>
                            <a href="{{ route('admin.' . $module . '.index') }}" class="btn btn-secondary w-sm">Hủy</a>
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Cài đặt khác</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Thứ tự sắp xếp</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $setting->sort_order ?? 0) }}">
                                    @error('sort_order')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $setting->is_active ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Kích hoạt</label>
                                    </div>
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
    <!-- End Page-content -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const valueInputs = document.querySelectorAll('.value-input');

    function showValueInput() {
        const selectedType = typeSelect.value;
        console.log('Selected type:', selectedType); // Debug

        // Hide all value inputs
        valueInputs.forEach(input => {
            input.style.display = 'none';
            const inputElement = input.querySelector('input, textarea, select');
            if (inputElement) {
                inputElement.disabled = true;
            }
        });

        // Show and enable appropriate input
        let targetInput = null;
        switch(selectedType) {
            case 'text':
                targetInput = document.getElementById('text-input');
                break;
            case 'number':
                targetInput = document.getElementById('number-input');
                break;
            case 'json':
            case 'html':
                targetInput = document.getElementById('textarea-input');
                break;
            case 'boolean':
                targetInput = document.getElementById('boolean-input');
                break;
        }

        if (targetInput) {
            targetInput.style.display = 'block';
            const inputElement = targetInput.querySelector('input, textarea, select');
            if (inputElement) {
                inputElement.disabled = false;
            }
            console.log('Showing input:', targetInput.id); // Debug
        }
    }

    // Event listener for type change
    typeSelect.addEventListener('change', showValueInput);

    // Initialize on page load
    showValueInput();
});
</script>
@endsection
