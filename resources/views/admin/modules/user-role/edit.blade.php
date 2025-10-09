@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Phân quyền')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Phân quyền cho: {{ $user->fullname }}</h4>
                            <p class="text-muted mb-0">{{ $user->email }} ({{ $user->username }})</p>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.user-role.update', ['id' => $user->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <!-- Hidden input to track assignment type -->
                                <input type="hidden" name="assignment_type" id="assignment_type" value="{{ $user->directPermissions->count() > 0 ? 'permissions' : 'roles' }}">

                                <!-- User Info -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card border border-primary">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">Thông tin người dùng</h6>
                                                <p><strong>Tên:</strong> {{ $user->fullname }}</p>
                                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                                <p><strong>Username:</strong> {{ $user->username }}</p>
                                                <p><strong>Level hiện tại:</strong> 
                                                    <span class="badge bg-info-subtle text-info">Level {{ $user->level }}</span>
                                                </p>
                                                <p><strong>Trạng thái:</strong> 
                                                    @if ($user->status == 1)
                                                        <span class="badge bg-success-subtle text-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border border-success">
                                            <div class="card-body">
                                                <h6 class="card-title text-success">Quyền hiện tại</h6>
                                                @if ($user->directPermissions->count() > 0 || $user->roles->count() > 0)
                                                    <div class="permission-badges" style="max-height: 200px; overflow-y: auto;">
                                                        @foreach ($user->getAllPermissions() as $permission)
                                                            <span class="badge bg-success-subtle text-success me-1 mb-1" style="font-size: 0.75em;">
                                                                {{ $permission->display_name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-muted">Chưa có quyền nào</p>
                                                @endif

                                                <hr>
                                                <h6 class="text-success">Vai trò hiện tại</h6>
                                                @if ($user->roles->count() > 0)
                                                    @foreach ($user->roles as $role)
                                                        <span class="badge bg-primary-subtle text-primary me-2 mb-2">
                                                            {{ $role->display_name }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <p class="text-muted">Chưa có vai trò nào</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabs for Role and Permission Assignment -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ $user->directPermissions->count() == 0 ? 'active' : '' }}" data-bs-toggle="tab" href="#rolesTab" role="tab">
                                                            <i class="ri-team-line"></i> Chọn vai trò
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link {{ $user->directPermissions->count() > 0 ? 'active' : '' }}" data-bs-toggle="tab" href="#permissionsTab" role="tab">
                                                            <i class="ri-settings-3-line"></i> Chọn quyền riêng lẻ
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content">
                                                    <!-- Roles Tab -->
                                                    <div class="tab-pane {{ $user->directPermissions->count() == 0 ? 'active' : '' }}" id="rolesTab" role="tabpanel">
                                                        <div class="row">
                                                            @foreach ($roles as $role)
                                                                <div class="col-md-4 mb-3">
                                                                    <div class="card border role-card {{ in_array($role->id, $userRoles) ? 'border-primary' : 'border-light' }}">
                                                                        <div class="card-body">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input role-checkbox" 
                                                                                       type="checkbox" 
                                                                                       name="roles[]" 
                                                                                       value="{{ $role->id }}" 
                                                                                       id="role_{{ $role->id }}"
                                                                                       {{ in_array($role->id, $userRoles) ? 'checked' : '' }}
                                                                                       onchange="updateRoleCard(this)">
                                                                                <label class="form-check-label fw-semibold" for="role_{{ $role->id }}">
                                                                                    {{ $role->display_name }}
                                                                                </label>
                                                                            </div>
                                                                            <small class="text-muted">{{ $role->name }}</small>
                                                                            @if ($role->description)
                                                                                <p class="mt-2 mb-0 small">{{ $role->description }}</p>
                                                                            @endif
                                                                            
                                                                            <!-- Show role permissions -->
                                                                            <div class="mt-2">
                                                                                <small class="text-muted fw-semibold">Quyền của vai trò:</small>
                                                                                <div class="role-permissions mt-1" style="max-height: 100px; overflow-y: auto;">
                                                                                    @foreach ($role->permissions as $permission)
                                                                                        <span class="badge bg-light text-dark me-1 mb-1" style="font-size: 0.7em;">
                                                                                            {{ $permission->display_name }}
                                                                                        </span>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>

                                                        <!-- Quick Actions for Roles -->
                                                        <div class="mt-3">
                                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAllRoles()">
                                                                <i class="ri-checkbox-multiple-line"></i> Chọn tất cả vai trò
                                                            </button>
                                                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearAllRoles()">
                                                                <i class="ri-close-circle-line"></i> Bỏ chọn tất cả vai trò
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Permissions Tab -->
                                                    <div class="tab-pane {{ $user->directPermissions->count() > 0 ? 'active' : '' }}" id="permissionsTab" role="tabpanel">
                                                        <div class="alert alert-info">
                                                            <i class="ri-information-line"></i>
                                                            <strong>Lưu ý:</strong> Chọn quyền riêng lẻ để tạo phân quyền tùy chỉnh (ví dụ: staff_level2 có quyền xem, tạo nhưng không có quyền sửa)
                                                        </div>

                                                        <!-- Permission Groups -->
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
                                                                                           {{ in_array($permission->id, $userDirectPermissions) ? 'checked' : '' }}>
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

                                                        <!-- Global Permission Actions -->
                                                        <div class="mt-3">
                                                            <button type="button" class="btn btn-outline-success btn-sm" onclick="selectAllPermissions()">
                                                                <i class="ri-checkbox-multiple-line"></i> Chọn tất cả quyền
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearAllPermissions()">
                                                                <i class="ri-close-circle-line"></i> Bỏ chọn tất cả quyền
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('admin.user-role.index') }}" class="btn btn-secondary">
                                                <i class="ri-arrow-left-line"></i> Quay lại
                                            </a>
                                            <button type="submit" class="btn btn-success">
                                                <i class="ri-save-line"></i> Lưu phân quyền
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
function updateRoleCard(checkbox) {
    const card = $(checkbox).closest('.role-card');
    if (checkbox.checked) {
        card.addClass('border-primary').removeClass('border-light');
    } else {
        card.addClass('border-light').removeClass('border-primary');
    }
}

// Role functions
function selectAllRoles() {
    $('.role-checkbox').prop('checked', true);
    $('.role-checkbox').each(function() {
        updateRoleCard(this);
    });
}

function clearAllRoles() {
    $('.role-checkbox').prop('checked', false);
    $('.role-checkbox').each(function() {
        updateRoleCard(this);
    });
}

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

$(document).ready(function() {
    // Initialize card borders based on checked state
    $('.role-checkbox').each(function() {
        updateRoleCard(this);
    });

    // Update role cards when changed
    $('.role-checkbox').change(function() {
        updateRoleCard(this);
    });

    // Initialize assignment_type based on active tab
    if ($('#permissionsTab').hasClass('active')) {
        $('#assignment_type').val('permissions');
    } else {
        $('#assignment_type').val('roles');
    }

    // Handle tab switching
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr("href");
        
        if (target === '#rolesTab') {
            $('#assignment_type').val('roles');
        } else if (target === '#permissionsTab') {
            $('#assignment_type').val('permissions');
        }
    });

    // Show confirmation before saving
    $('form').on('submit', function(e) {
        const assignmentType = $('#assignment_type').val();
        const selectedRoles = $('.role-checkbox:checked').length;
        const selectedPermissions = $('.permission-checkbox:checked').length;
        
        // Validation
        if (assignmentType === 'roles' && selectedRoles === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một vai trò!');
            return false;
        }
        
        if (assignmentType === 'permissions' && selectedPermissions === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một quyền!');
            return false;
        }

        const confirmMessage = assignmentType === 'roles' 
            ? `Bạn có chắc muốn gán ${selectedRoles} vai trò cho người dùng này?`
            : `Bạn có chắc muốn gán ${selectedPermissions} quyền riêng lẻ cho người dùng này?`;
        
        return confirm(confirmMessage);
    });
});
</script>
@endsection