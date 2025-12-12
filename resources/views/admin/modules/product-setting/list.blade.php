@extends('admin.master')
@section('module', $title)
@section('action', 'Danh sách')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Quản lý {{ $title }}</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="listjs-table" id="customerList">
                                <div class="row g-4 mb-3">
                                    <div class="col-sm-auto">
                                        <div>
                                            <a type="button" href="{{ route('admin.' . $module . '.create') }}"
                                                class="btn btn-success add-btn"><i
                                                    class="ri-add-line align-bottom me-1"></i> Thêm </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-auto">
                                        <button type="button" id="deleteSelectedItems" class="btn btn-danger add-btn">
                                            <i class="ri-delete-bin-5-line"></i> Xóa hết
                                        </button>
                                    </div>
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-sm-end">
                                            <div class="search-box ms-2">
                                                <form action="{{ route('admin.' . $module . '.index') }}" method="get"
                                                    style="display: flex">
                                                    @csrf
                                                    <select class="form-select mb-3" name="group">
                                                        <option value="" selected>Chọn nhóm </option>
                                                        <option value="display">Display</option>
                                                        <option value="policy">Policy</option>
                                                        <option value="general">General</option>
                                                        <option value="performance">Performance</option>
                                                    </select>
                                                    <input type="text" class="form-control search" name="search"
                                                        placeholder="Search..." autocomplete="off" id="search-options"
                                                        value="{{ request('search') }}">
                                                    <button class="btn btn-outline-secondary" type="submit"><i
                                                            class="ri-search-line search-icon"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-card mt-3 mb-1">
                                    <table class="table align-middle table-nowrap" id="customerTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" style="width: 50px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkAll"
                                                            value="option">
                                                    </div>
                                                </th>
                                                <th>Key</th>
                                                <th>Value</th>
                                                <th>Type</th>
                                                <th>Group</th>
                                                <th>Trạng thái</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($settings) > 0)
                                                @foreach($settings as $item)
                                                    <tr>
                                                        <th scope="row">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="uuids[]"
                                                                    value="{{ $item->uuid }}">
                                                            </div>
                                                        </th>
                                                        <td>
                                                            <strong>{{ $item->key }}</strong>
                                                            <br><small class="text-muted">Order: {{ $item->sort_order }}</small>
                                                        </td>
                                                        <td>
                                                            <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                                @if($item->type == 'html')
                                                                    <span class="badge bg-info">HTML Content</span>
                                                                @elseif($item->type == 'json')
                                                                    <span class="badge bg-info">JSON Data</span>
                                                                @elseif($item->type == 'boolean')
                                                                    @if($item->value == 'true')
                                                                        <span class="badge bg-success">Có / Bật</span>
                                                                    @else
                                                                        <span class="badge bg-danger">Không / Tắt</span>
                                                                    @endif
                                                                @else
                                                                    {{ Str::limit($item->value, 50) }}
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary">{{ $item->type }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-secondary">{{ $item->group }}</span>
                                                        </td>
                                                        <td class="status">
                                                                                                                         <div
                                                                 class="form-check form-switch form-switch-success mb-3">
                                                                  <input class="form-check-input status-checkbox"
                                                                      type="checkbox" role="switch"
                                                                      value="{{ $item->is_active }}"
                                                                      data-uuid="{{ $item->uuid }}" data-field="is_active"
                                                                      data-status="{{ $item->is_active }}"
                                                                      {{ $item->is_active == 1 ? 'checked' : '' }}>
                                                             </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <div class="edit">
                                                                    <a href="{{ route('admin.' . $module . '.edit', $item->uuid) }}"
                                                                        class="btn btn-sm btn-success edit-item-btn">Sửa</a>
                                                                </div>
                                                                <div class="remove">
                                                                    <a href="{{ route('admin.' . $module . '.destroy', $item->uuid) }}"
                                                                        class="btn btn-sm btn-danger remove-item-btn"
                                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <div class="pagination-wrap hstack gap-2">
                                        {{ $settings->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- container-fluid -->
    </div>

    @include('admin.ajax.status')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút "Xóa hết"
    const deleteSelectedItemsBtn = document.getElementById('deleteSelectedItems');
    
    if (deleteSelectedItemsBtn) {
        deleteSelectedItemsBtn.addEventListener('click', function() {
            // Kiểm tra xem có checkbox nào được chọn không
            const checkedBoxes = document.querySelectorAll('input[name="uuids[]"]:checked');
            
            if (checkedBoxes.length === 0) {
                toast('Vui lòng chọn ít nhất một setting để xóa!', 'error');
                return;
            }
            
            // Hiển thị confirm dialog
            if (confirm(`Bạn có chắc chắn muốn xóa ${checkedBoxes.length} setting đã chọn?`)) {
                // Tạo form và submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.product-setting.destroyAll") }}';
                
                // Thêm CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                // Thêm các UUID đã chọn
                checkedBoxes.forEach(checkbox => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'uuids[]';
                    hiddenInput.value = checkbox.value;
                    form.appendChild(hiddenInput);
                });
                
                // Submit form
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    // Xử lý checkbox "Chọn tất cả"
    const checkAllCheckbox = document.getElementById('checkAll');
    const childCheckboxes = document.querySelectorAll('input[name="uuids[]"]');
    
    if (checkAllCheckbox) {
        checkAllCheckbox.addEventListener('change', function() {
            childCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});
</script>
@endpush


