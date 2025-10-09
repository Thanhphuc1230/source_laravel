@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Danh sách')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Quản lý vai trò</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.user-role.index') }}">Phân quyền người dùng</a></li>
                                <li class="breadcrumb-item active">Quản lý vai trò</li>
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
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="flex-grow-1">
                                    <a href="{{ route('admin.user-role.roles.create') }}" class="btn btn-success add-btn">
                                        <i class="ri-add-fill me-1 align-bottom"></i> Tạo vai trò mới
                                    </a>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="hstack text-nowrap gap-2">
                                        <a href="{{ route('admin.user-role.index') }}" class="btn btn-soft-secondary">
                                            <i class="ri-arrow-left-line align-bottom me-1"></i> Quay lại danh sách user
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive table-card mb-1">
                                <table class="table table-nowrap table-striped-columns mb-0">
                                    <thead class="table-light text-muted">
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên vai trò</th>
                                            <th>Tên hiển thị</th>
                                            <th>Mô tả</th>
                                            <th>Số quyền</th>
                                            <th>Số người dùng</th>
                                            <th>Ngày tạo</th>
                                            <th class="text-end">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @forelse ($roles as $role)
                                            <tr>
                                                <td>{{ $role->id }}</td>
                                                <td>
                                                    <span class="badge bg-primary-subtle text-primary">{{ $role->name }}</span>
                                                </td>
                                                <td class="fw-semibold">{{ $role->display_name }}</td>
                                                <td>
                                                    @if ($role->description)
                                                        <span class="text-muted">{{ Str::limit($role->description, 50) }}</span>
                                                    @else
                                                        <span class="text-muted fst-italic">Chưa có mô tả</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-info-subtle text-info">
                                                        {{ $role->permissions->count() }} quyền
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success">
                                                        {{ $role->users->count() }} người dùng
                                                    </span>
                                                </td>
                                                <td>{{ $role->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="d-flex gap-2 justify-content-end">
                                                        <div class="edit">
                                                            <a href="{{ route('admin.user-role.roles.edit', ['id' => $role->id]) }}" 
                                                               class="btn btn-sm btn-success edit-item-btn">
                                                               <i class="ri-pencil-fill"></i> Sửa
                                                            </a>
                                                        </div>
                                                        @if ($role->users->count() == 0)
                                                            <div class="remove">
                                                                <form action="{{ route('admin.user-role.roles.destroy', ['id' => $role->id]) }}" 
                                                                      method="POST" 
                                                                      onsubmit="return confirm('Bạn có chắc muốn xóa vai trò này?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger remove-item-btn">
                                                                        <i class="ri-delete-bin-fill"></i> Xóa
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @else
                                                            <button class="btn btn-sm btn-secondary" disabled title="Không thể xóa vai trò đang được sử dụng">
                                                                <i class="ri-delete-bin-fill"></i> Xóa
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="ri-folder-open-line display-6"></i>
                                                        <p class="mt-2">Chưa có vai trò nào được tạo</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- container-fluid -->
    </div>
@endsection