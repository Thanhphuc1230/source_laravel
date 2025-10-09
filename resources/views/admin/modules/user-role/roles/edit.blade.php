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
                        <div class="card-header">
                            <h4 class="card-title mb-0">Thông tin vai trò</h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.user-role.roles.update', ['id' => $role->id]) }}" method="POST">
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
                                                      id="description" name="description" rows="3" 
                                                      placeholder="Mô tả vai trò...">{{ old('description', $role->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Role Usage Info -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="ri-information-line"></i>
                                            <strong>Thông tin sử dụng:</strong> Vai trò này đang được sử dụng bởi <strong>{{ $role->users->count() }}</strong> người dùng.
                                        </div>
                                    </div>
                                </div>

                                <!-- Permissions Section -->
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="mb-3">Gán quyền cho vai trò</h5>
                                        
                                        <div class="mb-3">
                                            <button type="button" class="btn btn-outline-success btn-sm" onclick="selectAllPermissions()">
                                                <i class="ri-checkbox-multiple-line"></i> Chọn tất cả
                                            </button>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearAllPermissions()">
                                                <i class="ri-close-circle-line"></i> Bỏ chọn tất cả
                                            </button>
                                        </div>

                                        @foreach ($permissionGroups as $groupName => $groupPermissions)
                                            <div class="card border border-light mb-3">
                                                <div class="card-header bg-light">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0 text-capitalize">
                                                            <i class="ri-folder-line"></i> {{ ucfirst($groupName) }}
                                                        </h6>
                                                        <div>
                                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                                    onclick="selectGroupPermissions('{{ $groupName }}')">
                                                                Chọn tất cả
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                                    onclick="clearGroupPermissions('{{ $groupName }}')">
                                                                Bỏ chọn
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach ($groupPermissions as $permission)
                                                            <div class="col-md-3 mb-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox" 
                                                                           type="checkbox" 
                                                                           name="permissions[]" 
                                                                           value="{{ $permission->id }}" 
                                                                           id="permission_{{ $permission->id }}"
                                                                           data-group="{{ $groupName }}"
                                                                           {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                        <small>{{ $permission->display_name }}</small>
                                                                        <br><span class="text-muted" style="font-size: 0.7em;">{{ $permission->name }}</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('admin.user-role.roles.index') }}" class="btn btn-secondary">
                                                <i class="ri-arrow-left-line"></i> Quay lại
                                            </a>
                                            <button type="submit" class="btn btn-success">
                                                <i class="ri-save-line"></i> Cập nhật vai trò
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

@section('script')
<script>
// Permission functions
function selectAllPermissions() {
    $('.permission-checkbox').prop('checked', true);
}

function clearAllPermissions() {
    $('.permission-checkbox').prop('checked', false);
}

function selectGroupPermissions(groupName) {
    $('.permission-checkbox[data-group="' + groupName + '"]').prop('checked', true);
}

function clearGroupPermissions(groupName) {
    $('.permission-checkbox[data-group="' + groupName + '"]').prop('checked', false);
}
</script>
@endsection