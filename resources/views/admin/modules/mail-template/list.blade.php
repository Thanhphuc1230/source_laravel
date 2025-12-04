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
                                    @hasPermission('mail-template.create')
                                        <div class="col-sm-auto">
                                            <div>
                                                <a type="button" href="{{ route('admin.mail-template.create') }}"
                                                    class="btn btn-success add-btn"><i
                                                        class="ri-add-line align-bottom me-1"></i> Thêm </a>
                                            </div>
                                        </div>
                                    @endhasPermission
                                </div>

                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <div class="table-responsive table-card mt-3 mb-1">
                                    <table class="table align-middle table-nowrap" id="customerTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="sort">ID</th>
                                                <th class="sort">Tên template</th>
                                                <th class="sort">Tiêu đề</th>
                                                <th class="sort">Loại</th>
                                                <th class="sort">Trạng thái</th>
                                                <th class="sort">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($templates) > 0)
                                                @foreach ($templates as $template)
                                                    <tr>
                                                        <td>{{ $template->id }}</td>
                                                        <td>{{ $template->name }}</td>
                                                        <td>{{ $template->subject }}</td>
                                                        <td>
                                                            @if($template->type == 'order')
                                                                <span class="badge bg-primary">Đơn hàng</span>
                                                            @elseif($template->type == 'contact')
                                                                <span class="badge bg-info">Liên hệ</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($template->is_active)
                                                                <span class="badge bg-success">Hoạt động</span>
                                                            @else
                                                                <span class="badge bg-secondary">Không hoạt động</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                @hasPermission('mail-template.edit')
                                                                    <div class="edit">
                                                                        <a href="{{ route('admin.mail-template.edit', $template->id) }}"
                                                                            class="btn btn-sm btn-success edit-item-btn">Sửa</a>
                                                                    </div>
                                                                @endhasPermission
                                                                @hasPermission('mail-template.set-active')
                                                                    <div class="edit">
                                                                        <form action="{{ route('admin.mail-template.set-active', $template->id) }}"
                                                                              method="POST" style="display: inline;">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-warning edit-item-btn">
                                                                                {{ $template->is_active ? 'Tắt' : 'Bật' }}
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @endhasPermission
                                                                @hasPermission('mail-template.delete')
                                                                    <div class="remove">
                                                                        <form action="{{ route('admin.mail-template.delete', $template->id) }}"
                                                                              method="POST" style="display: inline;">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-danger remove-item-btn"
                                                                                    onclick="return confirm('Xác nhận xóa {{ $nameItem }} ?')">Xóa</button>
                                                                        </form>
                                                                    </div>
                                                                @endhasPermission
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6" style="text-align:center">Chưa có dữ liệu</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                @if(count($templates) > 0)
                                    <div class="d-flex justify-content-center">
                                        {{ $templates->links() }}
                                    </div>
                                @endif
                            </div>
                        </div><!-- end card -->
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
        @include('admin.ajax.update-stt')
    @endsection