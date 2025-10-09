@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Danh sách')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Quản lý {{ $nameItem }}</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="listjs-table" id="customerList">
                                <div class="row g-4 mb-3">
                                    <div class="col-sm-auto">
                                        <div class="d-flex gap-2">
                                            <button type="button" id="bulkAssignRole" class="btn btn-primary add-btn">
                                                <i class="ri-user-settings-line"></i> Phân quyền hàng loạt
                                            </button>
                                            <a href="{{ route('admin.user-role.roles.index') }}" class="btn btn-success">
                                                <i class="ri-settings-3-line"></i> Quản lý vai trò
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-sm-end">
                                            <div class="search-box ms-2">
                                                <form action="{{ route('admin.' . $nameClass . '.index') }}" method="get">
                                                    @csrf
                                                    <input type="text" class="form-control search" name="search"
                                                        value="{{ request('search') }}" placeholder="Tìm kiếm tên, username, email...">
                                                    <i class="ri-search-line search-icon"></i>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive table-card mt-3 mb-1">
                                    <table class="table align-middle table-nowrap" id="customerTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th class="sort">ID</th>
                                                <th class="sort">Tên đầy đủ</th>
                                                <th class="sort">Username</th>
                                                <th class="sort">Email</th>
                                                <th class="sort">Level</th>
                                                <th class="sort">Vai trò hiện tại</th>
                                                <th class="sort">Trạng thái</th>
                                                <th class="sort">Ngày tạo</th>
                                                <th class="sort">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($list) > 0)
                                                @foreach ($list as $item)
                                                    <tr>
                                                        <td><input class="form-check-input user-checkbox" type="checkbox"
                                                                name="user_ids[]" value="{{ $item->id }}"></td>
                                                        <td>{{ $item->id }}</td>
                                                        <td>{{ $item->fullname }}</td>
                                                        <td>{{ $item->username }}</td>
                                                        <td>{{ $item->email }}</td>
                                                        <td>
                                                            <span class="badge bg-info-subtle text-info">
                                                                Level {{ $item->level }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if ($item->roles->count() > 0)
                                                                @foreach ($item->roles as $role)
                                                                    <span class="badge bg-primary-subtle text-primary me-1">
                                                                        {{ $role->display_name }}
                                                                    </span>
                                                                @endforeach
                                                            @else
                                                                <span class="badge bg-secondary-subtle text-secondary">
                                                                    Chưa có vai trò
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="status">
                                                            @if ($item->status == 1)
                                                                <span class="badge bg-success-subtle text-success text-uppercase">Active</span>
                                                            @else
                                                                <span class="badge bg-danger-subtle text-danger text-uppercase">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td class="date">
                                                            {{ $item->created_at ? $item->created_at->format('d-m-Y') : '' }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <div class="edit">
                                                                    <a href="{{ route('admin.' . $nameClass . '.edit', ['id' => $item->id]) }}"
                                                                        class="btn btn-sm btn-success edit-item-btn">
                                                                        <i class="ri-user-settings-line"></i> Phân quyền
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" style="text-align:center">Chưa có dữ liệu</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <div class="pagination-wrap hstack gap-2" style="display: flex;">
                                        {!! $list->appends(request()->except('page'))->links() !!}
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end col -->
            </div>

        </div>
        <!-- container-fluid -->
    </div>

    <!-- Bulk Role Assignment Modal -->
    <div class="modal fade" id="bulkRoleModal" tabindex="-1" aria-labelledby="bulkRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkRoleModalLabel">Phân quyền hàng loạt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkRoleForm" action="{{ route('admin.user-role.bulk-update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div id="selectedUsersInfo" class="mb-3"></div>
                        
                        <div class="mb-3">
                            <label class="form-label">Chọn vai trò:</label>
                            @php $roles = \App\Models\Role::all(); @endphp
                            @foreach ($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" 
                                           value="{{ $role->id }}" id="bulk_role_{{ $role->id }}">
                                    <label class="form-check-label" for="bulk_role_{{ $role->id }}">
                                        {{ $role->display_name }}
                                        <small class="text-muted">({{ $role->name }})</small>
                                        <br><small class="text-info">{{ $role->description }}</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="alert alert-warning">
                            <i class="ri-alert-line"></i>
                            <strong>Lưu ý:</strong> Thao tác này sẽ thay thế toàn bộ vai trò hiện tại của người dùng được chọn.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật phân quyền</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
$(document).ready(function() {
    // Check all functionality
    $('#checkAll').change(function() {
        $('.user-checkbox').prop('checked', this.checked);
    });

    // Update checkAll when individual checkboxes change
    $(document).on('change', '.user-checkbox', function() {
        var totalCheckboxes = $('.user-checkbox').length;
        var checkedCheckboxes = $('.user-checkbox:checked').length;
        $('#checkAll').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // Bulk role assignment
    $('#bulkAssignRole').click(function() {
        const selectedUsers = $('.user-checkbox:checked');
        
        if (selectedUsers.length === 0) {
            alert('Vui lòng chọn ít nhất một người dùng!');
            return;
        }

        // Clear previous user IDs
        $('#bulkRoleForm input[name="user_ids[]"]').remove();
        
        // Add selected user IDs to form
        let userInfo = '<p><strong>Đã chọn ' + selectedUsers.length + ' người dùng:</strong></p><ul>';
        selectedUsers.each(function() {
            const userId = $(this).val();
            const userName = $(this).closest('tr').find('td:nth-child(3)').text().trim(); // fullname column
            const userEmail = $(this).closest('tr').find('td:nth-child(5)').text().trim(); // email column
            
            $('#bulkRoleForm').append('<input type="hidden" name="user_ids[]" value="' + userId + '">');
            userInfo += '<li>' + userName + ' (' + userEmail + ')</li>';
        });
        userInfo += '</ul>';
        
        $('#selectedUsersInfo').html(userInfo);
        
        // Clear previous role selections
        $('#bulkRoleForm input[name="roles[]"]').prop('checked', false);
        
        // Show modal
        $('#bulkRoleModal').modal('show');
    });

    // Reset checkboxes when modal is closed
    $('#bulkRoleModal').on('hidden.bs.modal', function() {
        $('#checkAll').prop('checked', false);
        $('.user-checkbox').prop('checked', false);
        $('#bulkRoleForm input[name="roles[]"]').prop('checked', false);
    });

    // Validate bulk form submission
    $('#bulkRoleForm').on('submit', function(e) {
        var selectedRoles = $('#bulkRoleForm input[name="roles[]"]:checked').length;
        if (selectedRoles === 0) {
            e.preventDefault();
            alert('Vui lòng chọn ít nhất một vai trò!');
            return false;
        }
        return true;
    });
});
</script>
@endsection