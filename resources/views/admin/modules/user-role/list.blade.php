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
                                                    <td colspan="9" style="text-align:center">Chưa có dữ liệu</td>
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
@endsection