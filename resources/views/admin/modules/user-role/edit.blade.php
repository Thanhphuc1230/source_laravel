@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Phân quyền')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Phân quyền người dùng</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.user-role.index') }}">Phân quyền</a></li>
                                <li class="breadcrumb-item active">Thiết lập</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex bg-light">
                            <h4 class="card-title mb-0 flex-grow-1">Thiết lập vai trò và quyền hạn cho: <strong class="text-primary">{{ $user->fullname }}</strong></h4>
                            <div class="flex-shrink-0">
                                <span class="badge bg-info-subtle text-info fs-12">Level {{ $user->level }}</span>
                                <span class="badge bg-secondary-subtle text-secondary ms-1 fs-12">{{ $user->username }}</span>
                            </div>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.user-role.update', ['id' => $user->id]) }}" method="POST" id="permissionsForm">
                                @csrf
                                @method('PUT')

                                <!-- Row 1: Split Roles and Permissions Grid -->
                                <div class="row">
                                    <!-- Left Column: Roles Selection (col-lg-4) -->
                                    <div class="col-lg-4 border-end">
                                        <div class="mb-4">
                                            <h5 class="fs-15 mb-2 text-primary"><i class="ri-team-line align-middle me-1"></i> Chọn Vai Trò (Roles)</h5>
                                            <p class="text-muted small mb-0">Các quyền của vai trò đã chọn sẽ tự động được check và hiển thị nổi bật ở bảng bên cạnh.</p>
                                        </div>

                                        <div class="roles-container">
                                            @foreach ($roles as $role)
                                                <div class="card border role-card mb-3 shadow-none transition-all" style="cursor: pointer; border-radius: 8px;">
                                                    <div class="card-body p-3">
                                                        <div class="form-check form-check-primary d-flex align-items-start">
                                                            <input class="form-check-input role-checkbox me-2 mt-1" 
                                                                   type="checkbox" 
                                                                   name="roles[]" 
                                                                   value="{{ $role->id }}" 
                                                                   id="role_{{ $role->id }}"
                                                                   data-display-name="{{ $role->display_name }}"
                                                                   {{ in_array($role->id, $userRoles) ? 'checked' : '' }}>
                                                            <div class="flex-grow-1" onclick="toggleRoleCheckbox('{{ $role->id }}')">
                                                                <label class="form-check-label fw-semibold text-dark fs-14" style="cursor: pointer;">
                                                                    {{ $role->display_name }}
                                                                </label>
                                                                <div class="text-muted small mt-1">{{ $role->description ?: 'Không có mô tả.' }}</div>
                                                                <div class="mt-2">
                                                                    <span class="badge bg-primary-subtle text-primary" style="font-size: 0.75em;">
                                                                        {{ $role->permissions->count() }} quyền hạn
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Right Column: Detailed Permissions Grid (col-lg-8) -->
                                    <div class="col-lg-8 ps-lg-4">
                                        <div class="d-sm-flex align-items-center justify-content-between mb-3">
                                            <div>
                                                <h5 class="fs-15 text-primary mb-1"><i class="ri-settings-3-line align-middle me-1"></i> Tùy Chỉnh Quyền Hạn Chi Tiết</h5>
                                                <p class="text-muted small mb-0">Tích chọn các quyền riêng lẻ mong muốn bổ sung ngoài các vai trò đã chọn.</p>
                                            </div>
                                            <!-- Permissions Search Box -->
                                            <div class="search-box mt-2 mt-sm-0" style="max-width: 250px;">
                                                <input type="text" class="form-control" id="permissionSearch" placeholder="Tìm nhanh quyền hạn...">
                                                <i class="ri-search-line search-icon"></i>
                                            </div>
                                        </div>

                                        <!-- Quick Global actions for permissions -->
                                        <div class="bg-light p-2 rounded mb-3 d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-success" onclick="selectAllDirectPermissions()">
                                                <i class="ri-checkbox-multiple-line"></i> Chọn tất cả quyền
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearAllDirectPermissions()">
                                                <i class="ri-close-circle-line"></i> Bỏ chọn tất cả
                                            </button>
                                        </div>

                                        <!-- Permission Groups List (Table Layout) -->
                                        <div class="permission-groups-container" style="max-height: 650px; overflow-y: auto; padding-right: 5px;">
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
                                                                                           {{ in_array($permission->id, $userDirectPermissions) ? 'checked' : '' }}>
                                                                                    <label class="form-check-label" for="permission_{{ $permission->id }}" style="cursor: pointer; line-height: 1.2;">
                                                                                        <span class="fw-medium text-dark d-block" style="font-size: 0.85em;">{{ $permission->display_name }}</span>
                                                                                        <span class="text-muted" style="font-size: 0.7em;">{{ $permission->name }}</span>
                                                                                        <!-- Inherited Badge Place Holder -->
                                                                                        <span class="inherited-badge-placeholder"></span>
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
                                </div>

                                <hr class="my-4">

                                <!-- Bottom Form Actions -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('admin.user-role.index') }}" class="btn btn-light btn-label waves-effect waves-light">
                                                <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i> Quay lại
                                            </a>
                                            <button type="submit" class="btn btn-success btn-label waves-effect waves-light">
                                                <i class="ri-save-line label-icon align-middle fs-16 me-2"></i> Lưu phân quyền
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
    // Maps roles to their list of permission IDs
    const rolePermissionsMap = {
        @foreach($roles as $role)
            "{{ $role->id }}": @json($role->permissions->pluck('id')->toArray()),
        @endforeach
    };

    // The user's original direct permissions
    const userDirectPermissions = @json($userDirectPermissions);
    
    // First load trigger
    window.firstLoad = true;

    function toggleRoleCheckbox(roleId) {
        const checkbox = document.getElementById(`role_${roleId}`);
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            $(checkbox).trigger('change');
        }
    }

    function updateRoleCardStyle(checkbox) {
        const card = $(checkbox).closest('.role-card');
        if (checkbox.checked) {
            card.addClass('border-primary bg-primary-subtle-light').removeClass('border-light');
        } else {
            card.addClass('border-light bg-white').removeClass('border-primary bg-primary-subtle-light');
        }
    }

    // Evaluates permission checked & disabled state dynamically based on checked roles
    function updatePermissionsInheritance() {
        // Collect currently checked direct permissions (only enabled ones)
        const currentCheckedDirect = new Set();
        $('.permission-checkbox:not(:disabled):checked').each(function() {
            currentCheckedDirect.add(parseInt($(this).val()));
        });
        
        // If this is the initial page load, populate checked permissions from the db
        if (window.firstLoad) {
            userDirectPermissions.forEach(id => currentCheckedDirect.add(parseInt(id)));
            window.firstLoad = false;
        }

        // Reset all fields
        $('.permission-checkbox').prop('checked', false).prop('disabled', false);
        $('.inherited-badge-placeholder').empty();
        $('.permission-item-wrapper').removeClass('bg-success-subtle border-success');

        // Group inheritance sources
        const inheritedBy = {}; // permissionId => Array of role names

        // Scan checked roles
        $('.role-checkbox:checked').each(function() {
            const roleId = $(this).val();
            const roleName = $(this).attr('data-display-name');
            const permissionIds = rolePermissionsMap[roleId] || [];
            
            permissionIds.forEach(pId => {
                if (!inheritedBy[pId]) {
                    inheritedBy[pId] = [];
                }
                inheritedBy[pId].push(roleName);
            });
        });

        // Set state for each permission checkbox
        $('.permission-checkbox').each(function() {
            const pId = parseInt($(this).val());
            const itemWrapper = $(this).closest('.permission-item-wrapper');
            
            if (inheritedBy[pId]) {
                // Granted by Role: check, disable, highlight, and display label
                $(this).prop('checked', true).prop('disabled', true);
                itemWrapper.addClass('bg-success-subtle border-success');
                
                const rolesStr = inheritedBy[pId].join(', ');
                const placeholder = itemWrapper.find('.inherited-badge-placeholder');
                placeholder.html(`
                    <span class="badge bg-success text-success-light fs-10" style="padding: 2px 4px; display: inline-block; margin-top: 2px;">
                        <i class="ri-team-line me-1"></i> ${rolesStr}
                    </span>
                `);
            } else {
                // Direct permission: set checked state based on saved direct selections
                if (currentCheckedDirect.has(pId)) {
                    $(this).prop('checked', true);
                }
            }
        });
    }

    // Permissions action
    function selectAllDirectPermissions() {
        $('.permission-checkbox:not(:disabled)').prop('checked', true);
    }

    function clearAllDirectPermissions() {
        $('.permission-checkbox:not(:disabled)').prop('checked', false);
    }

    function selectGroupPermissions(groupName) {
        $(`.permission-checkbox[data-group="${groupName}"]:not(:disabled)`).prop('checked', true);
    }

    function clearGroupPermissions(groupName) {
        $(`.permission-checkbox[data-group="${groupName}"]:not(:disabled)`).prop('checked', false);
    }

    $(document).ready(function() {
        // Init styles on load
        $('.role-checkbox').each(function() {
            updateRoleCardStyle(this);
        });
        updatePermissionsInheritance();

        // Listen for changes
        $('.role-checkbox').change(function() {
            updateRoleCardStyle(this);
            updatePermissionsInheritance();
        });

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

        // Form submit validation and formatting
        $('#permissionsForm').on('submit', function(e) {
            const selectedRoles = $('.role-checkbox:checked').length;
            
            // Temporary enable checkboxes to allow direct permission values to submit
            $('.permission-checkbox').prop('disabled', false);

            const selectedPermissions = $('.permission-checkbox:checked').length;

            if (selectedRoles === 0 && selectedPermissions === 0) {
                e.preventDefault();
                // Restore disabled state on cancel
                updatePermissionsInheritance();
                alert('Vui lòng chọn ít nhất một vai trò hoặc một quyền hạn!');
                return false;
            }

            return true;
        });
    });
</script>
<style>
    .role-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }
    .role-card.border-primary {
        background-color: rgba(37, 64, 143, 0.03) !important;
    }
    .hover-shadow-sm:hover {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        border-color: #adb5bd !important;
    }
    .form-check-success .form-check-input:checked {
        background-color: #0ab39c;
        border-color: #0ab39c;
    }
    .text-success-light {
        color: #0ab39c !important;
    }
    .bg-primary-subtle-light {
        background-color: rgba(37, 64, 143, 0.03) !important;
        border-color: #25408f !important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
    .permission-group-row td {
        padding: 0.75rem;
    }
    .table > :not(caption) > * > * {
        padding: 0.75rem;
    }
</style>
@endpush