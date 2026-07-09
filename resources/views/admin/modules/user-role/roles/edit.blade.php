@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Chỉnh sửa')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Chỉnh sửa vai trò: {{ $role->display_name }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.user-role.index') }}">Phân quyền người dùng</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.user-role.roles.index') }}">Quản lý vai trò</a></li>
                                <li class="breadcrumb-item active">Chỉnh sửa</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0 text-dark">Thông tin vai trò</h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.user-role.roles.update', ['id' => $role->id]) }}" method="POST" id="roleForm">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Tên vai trò <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $role->name) }}" 
                                                   placeholder="admin, manager, staff..." required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Tên vai trò không dấu, viết thường, không có khoảng trắng</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="display_name" class="form-label">Tên hiển thị <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                                   id="display_name" name="display_name" value="{{ old('display_name', $role->display_name) }}" 
                                                   placeholder="Quản trị viên, Quản lý..." required>
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Mô tả</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="2" 
                                                      placeholder="Mô tả vai trò...">{{ old('description', $role->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Role Usage Info -->
                                <div class="alert alert-info py-2 px-3 mb-4">
                                    <i class="ri-information-line align-middle me-1"></i>
                                    <strong>Thông tin sử dụng:</strong> Vai trò này đang được sử dụng bởi <strong>{{ $role->users->count() }}</strong> người dùng.
                                </div>

                                <!-- Permissions Section -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-sm-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                            <h5 class="mb-0 text-primary"><i class="ri-settings-3-line align-middle me-1"></i> Gán quyền cho vai trò</h5>
                                            <!-- Permissions Search Box -->
                                            <div class="search-box mt-2 mt-sm-0" style="max-width: 250px;">
                                                <input type="text" class="form-control" id="permissionSearch" placeholder="Tìm nhanh quyền hạn...">
                                                <i class="ri-search-line search-icon"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-light p-2 rounded mb-3 d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="selectAllPermissions()">
                                                <i class="ri-checkbox-multiple-line"></i> Chọn tất cả
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearAllPermissions()">
                                                <i class="ri-close-circle-line"></i> Bỏ chọn tất cả
                                            </button>
                                        </div>

                                        <!-- Permission Groups List (Table Layout) -->
                                        <div class="card border shadow-none">
                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle mb-0">
                                                    <thead class="table-light text-dark">
                                                        <tr>
                                                            <th style="width: 25%;">Module / Nhóm quyền</th>
                                                            <th style="width: 75%;">Quyền hạn chi tiết (Actions)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($permissionGroups as $groupName => $groupPermissions)
                                                            <tr class="permission-group-row" data-group-name="{{ $groupName }}">
                                                                <td class="bg-light-subtle">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <span class="fw-semibold text-capitalize text-dark">
                                                                            <i class="ri-folder-open-line text-warning align-middle me-1"></i>
                                                                            {{ str_replace(['_', '-'], ' ', $groupName) }}
                                                                        </span>
                                                                        <div class="btn-group">
                                                                            <button type="button" class="btn btn-link btn-sm text-primary p-0 me-2" style="font-size: 0.8em; text-decoration: none;" onclick="selectGroupPermissions('{{ $groupName }}')">Chọn</button>
                                                                            <button type="button" class="btn btn-link btn-sm text-muted p-0" style="font-size: 0.8em; text-decoration: none;" onclick="clearGroupPermissions('{{ $groupName }}')">Bỏ</button>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex flex-wrap gap-2">
                                                                        @foreach ($groupPermissions as $permission)
                                                                            <div class="form-check form-check-success permission-item-wrapper p-1 px-2 rounded border border-dashed hover-shadow-sm d-inline-flex align-items-center" style="min-height: 38px; margin-bottom: 0;" data-permission-text="{{ strtolower($permission->display_name) }} {{ strtolower($permission->name) }}">
                                                                                <input class="form-check-input permission-checkbox me-2" 
                                                                                       type="checkbox" 
                                                                                       name="permissions[]" 
                                                                                       value="{{ $permission->id }}" 
                                                                                       id="permission_{{ $permission->id }}"
                                                                                       data-group="{{ $groupName }}"
                                                                                       {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                                                <label class="form-check-label" for="permission_{{ $permission->id }}" style="cursor: pointer; line-height: 1.2;">
                                                                                    <span class="fw-medium text-dark d-block" style="font-size: 0.85em;">{{ $permission->display_name }}</span>
                                                                                    <span class="text-muted" style="font-size: 0.7em;">{{ $permission->name }}</span>
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="noResultsMsg" class="text-center p-4 text-muted" style="display: none;">
                                            <i class="ri-search-eye-line fs-24"></i>
                                            <p class="mt-2">Không tìm thấy quyền hạn nào phù hợp với từ khóa.</p>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Actions -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('admin.user-role.roles.index') }}" class="btn btn-light btn-label waves-effect waves-light">
                                                <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Quay lại
                                            </a>
                                            <button type="submit" class="btn btn-success btn-label waves-effect waves-light">
                                                <i class="ri-save-line label-icon align-middle fs-16 me-2"></i> Cập nhật vai trò
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Permission functions
    function selectAllPermissions() {
        $('.permission-checkbox').prop('checked', true);
    }

    function clearAllPermissions() {
        $('.permission-checkbox').prop('checked', false);
    }

    function selectGroupPermissions(groupName) {
        $(`.permission-checkbox[data-group="${groupName}"]`).prop('checked', true);
    }

    function clearGroupPermissions(groupName) {
        $(`.permission-checkbox[data-group="${groupName}"]`).prop('checked', false);
    }

    $(document).ready(function() {
        // Search feature to filter permissions dynamically
        $('#permissionSearch').on('keyup', function() {
            const value = $(this).val().toLowerCase().trim();
            let hasVisibleGroup = false;

            $('.permission-group-row').each(function() {
                let groupHasMatch = false;
                const groupRow = $(this);
                
                groupRow.find('.permission-item-wrapper').each(function() {
                    const text = $(this).attr('data-permission-text');
                    if (text.includes(value)) {
                        $(this).show();
                        groupHasMatch = true;
                    } else {
                        $(this).hide();
                    }
                });

                if (groupHasMatch) {
                    groupRow.show();
                    hasVisibleGroup = true;
                } else {
                    groupRow.hide();
                }
            });

            if (hasVisibleGroup) {
                $('#noResultsMsg').hide();
                $('.table-responsive').show();
            } else {
                $('#noResultsMsg').show();
                $('.table-responsive').hide();
            }
        });
    });
</script>
<style>
    .hover-shadow-sm:hover {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        border-color: #adb5bd !important;
    }
    .form-check-success .form-check-input:checked {
        background-color: #0ab39c;
        border-color: #0ab39c;
    }
    .permission-group-row td {
        padding: 0.75rem;
    }
    .table > :not(caption) > * > * {
        padding: 0.75rem;
    }
</style>
@endpush