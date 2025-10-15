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
                                        <div>
                                            <a type="button" id="deleteSelectedItems" class="btn btn-danger add-btn"><i
                                                    class="ri-delete-bin-5-line"></i>Xóa hết</a>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="d-flex justify-content-sm-end">
                                            <div class="search-box ms-2">
                                                <form action="{{ route('admin.' . $nameClass . '.index') }}" method="get">
                                                    @csrf
                                                    <input type="text" class="form-control search" name="search"
                                                        placeholder="Search...">
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
                                                <th class="sort">Tên</th>
                                                <th class="sort">Email</th>
                                                <th class="sort">Nội dung</th>
                                                <th class="sort">Bài viết ID</th>
                                                <th class="sort">Loại</th>
                                                <th class="sort">Hiển thị</th>
                                                <th class="sort">Ngày tạo</th>
                                                <th class="sort">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($list) > 0)
                                                <form id="delete-form-all" action="{{ route('admin.' . $nameClass . '.destroyAll') }}"
                                                    method="POST">
                                                    @csrf
                                                    @foreach ($list as $item)
                                                        <tr>
                                                            <td><input class="form-check-input" type="checkbox"
                                                                    name="uuids[]" value="{{ $item->uuid }}"></td>
                                                            <td>{{ $item->id_comment }}</td>
                                                            <td>{{ $item->name }}</td>
                                                            <td>{{ $item->email }}</td>
                                                            <td>{{ Str::limit($item->content, 50) }}</td>
                                                            <td>{{ $item->id_post }}</td>
                                                            <td>{{ $item->type_post_name }}</td>
                                                            <td class="status">
                                                                @if ($item->status == 1)
                                                                    <a href="{{ route('admin.' . $nameClass . '.status', ['uuid' => $item->uuid, 'status' => 0, 'field' => 'status']) }}"
                                                                        onclick="return confirm('Xác nhận tắt kích hoạt {{ $nameItem }} ?')">
                                                                        <span
                                                                            class="badge bg-success-subtle text-success text-uppercase">Active</span>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('admin.' . $nameClass . '.status', ['uuid' => $item->uuid, 'status' => 1, 'field' => 'status']) }}"
                                                                        onclick="return confirm('Xác nhận kích hoạt {{ $nameItem }} ?')">
                                                                        <span
                                                                            class="badge bg-danger-subtle text-danger text-uppercase">Block</span>
                                                                    </a>
                                                                @endif
                                                            </td>

                                                            <td class="date">
                                                                {{ $item->created_at ? $item->created_at->format('d-m-Y') : '' }}
                                                            </td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    @hasPermission('comment.moderate')
                                                                    <div class="edit">
                                                                        <a href="{{ route('admin.' . $nameClass . '.edit', ['uuid' => $item->uuid]) }}"
                                                                            class="btn btn-sm btn-success edit-item-btn">Xem</a>
                                                                    </div>
                                                                    @endhasPermission
                                                                    @hasPermission('comment.delete')
                                                                    <div class="remove">
                                                                        <a href="{{ route('admin.' . $nameClass . '.destroy', ['uuid' => $item->uuid]) }}"
                                                                            class="btn btn-sm btn-danger remove-item-btn"
                                                                            onclick="return confirm('Xác nhận xóa {{ $nameItem }} ?')">Xóa</a>
                                                                    </div>
                                                                    @endhasPermission
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </form>
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

@endsection
