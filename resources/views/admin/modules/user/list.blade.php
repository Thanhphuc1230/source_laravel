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
                                    @hasPermission('user.create')
                                    <div class="col-sm-auto">
                                        <div>
                                            <a type="button" href="{{ route('admin.user.create') }}"
                                                class="btn btn-success add-btn"><i
                                                    class="ri-add-line align-bottom me-1"></i> Thêm </a>
                                        </div>
                                    </div>
                                    @endhasPermission
                                    @hasPermission('user.delete')
                                    <div class="col-sm-auto">
                                        <button type="button" id="deleteSelectedItems" class="btn btn-danger add-btn">
                                            <i class="ri-delete-bin-5-line"></i> Xóa đã chọn
                                        </button>
                                    </div>
                                    @endhasPermission
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-sm-end">
                                            <div class="search-box ms-2">
                                                <form action="{{ route('admin.user.index') }}" method="get"
                                                    style="display: flex; gap: 10px;">
                                                    @csrf
                                                    <select class="form-select" name="level" style="width: auto;">
                                                        <option value="">Tất cả cấp độ</option>
                                                        <option value="1" {{ request('level') == '1' ? 'selected' : '' }}>Admin</option>
                                                        <option value="2" {{ request('level') == '2' ? 'selected' : '' }}>Staff</option>
                                                        <option value="3" {{ request('level') == '3' ? 'selected' : '' }}>User</option>
                                                    </select>
                                                    <select class="form-select" name="status" style="width: auto;">
                                                        <option value="">Tất cả trạng thái</option>
                                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Khóa</option>
                                                    </select>
                                                    <input type="text" class="form-control search" name="search"
                                                        placeholder="Tìm kiếm..." value="{{ request('search') }}" style="height: 37.5px; width: 200px;">
                                                    <button type="submit"
                                                        class="btn btn-success w-lg waves-effect waves-light"
                                                        style="height: 37.5px">Tìm kiếm</button>
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
                                                <th class="sort">Họ tên</th>
                                                <th class="sort">Username</th>
                                                <th class="sort">Email</th>
                                                <th class="sort">SĐT</th>
                                                <th class="sort">Cấp độ</th>
                                                <th class="sort">Trạng thái</th>
                                                <th class="sort">Ngày tạo</th>
                                                <th class="sort">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($list) > 0)
                                                <form id="delete-form-all"
                                                    action="{{ route('admin.user.bulk-delete') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    @foreach ($list as $item)
                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox" name="uuids[]"
                                                                    value="{{ $item->uuid }}"></td>
                                                            <td>{{ $item->id }}</td>
                                                            <td>{{ $item->fullname }}</td>
                                                            <td>{{ $item->username }}</td>
                                                            <td>{{ $item->email }}</td>
                                                            <td>{{ $item->phone ?: '-' }}</td>
                                                            <td>
                                                                @if($item->level == 1)
                                                                    <span class="badge bg-danger">Admin</span>
                                                                @elseif($item->level == 2)
                                                                    <span class="badge bg-warning">Staff</span>
                                                                @else
                                                                    <span class="badge bg-info">User</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($item->status)
                                                                    <span class="badge bg-success">Hoạt động</span>
                                                                @else
                                                                    <span class="badge bg-secondary">Khóa</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    @hasPermission('user.edit')
                                                                    <a href="{{ route('admin.user.edit', $item->uuid) }}"
                                                                        class="btn btn-sm btn-warning">
                                                                        <i class="ri-edit-line"></i>
                                                                    </a>
                                                                    @endhasPermission
                                                                    @hasPermission('user.delete')
                                                                    @if($item->level != 1 && $item->id != auth()->id())
                                                                    <button type="button" class="btn btn-sm btn-danger delete-item"
                                                                        data-uuid="{{ $item->uuid }}"
                                                                        data-name="{{ $item->fullname }}">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                    @endif
                                                                    @endhasPermission
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </form>
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">Không có dữ liệu</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                @if (count($list) > 0)
                                <div class="d-flex justify-content-center">
                                    {{ $list->appends(request()->query())->links() }}
                                </div>
                                @endif
                            </div>
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div><!-- end row -->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa người dùng <strong id="deleteUserName"></strong>?</p>
                    <p class="text-danger">Hành động này không thể hoàn tác!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Check all functionality
    $('#checkAll').on('change', function() {
        $('.form-check-input[name="uuids[]"]').prop('checked', $(this).prop('checked'));
    });

    // Delete selected items
    $('#deleteSelectedItems').on('click', function() {
        const selectedItems = $('.form-check-input[name="uuids[]"]:checked');
        if (selectedItems.length === 0) {
            alert('Vui lòng chọn ít nhất một người dùng để xóa!');
            return;
        }

        if (confirm('Bạn có chắc chắn muốn xóa các người dùng đã chọn?')) {
            $('#delete-form-all').submit();
        }
    });

    // Delete single item
    $('.delete-item').on('click', function() {
        const userUuid = $(this).data('uuid');
        const userName = $(this).data('name');

        $('#deleteUserName').text(userName);
        $('#deleteForm').attr('action', '{{ route("admin.user.destroy", ":uuid") }}'.replace(':uuid', userUuid));

        $('#deleteModal').modal('show');
    });
});
</script>
@endpush