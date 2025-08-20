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
                                                <th class="sort">Họ và tên</th>
                                                <th class="sort">Email</th>
                                                <th class="sort">Số điện thoại</th>
                                                <th class="sort">Tiêu đề</th>
                                                <th class="sort">Nội dung</th>
                                                <th class="sort">Hiển thị</th>
                                                <th class="sort">Ngày cập nhật</th>
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
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $item->fullname }}</td>
                                                            <td>{{ $item->email }}</td>
                                                            <td>{{ $item->phone }}</td>
                                                            <td>{{ $item->subject }}</td>
                                                            <td>{{ Str::limit($item->message, 50) }}</td>
                                                            <td class="status">
                                                                @if ($item->status == 1)
                                                                    <a href="{{ route('admin.' . $nameClass . '.status', ['uuid' => $item->uuid, 'status' => 0, 'name' => 'status']) }}"
                                                                        onclick="return confirm('Xác nhận tắt kích hoạt {{ $nameItem }} ?')">
                                                                        <span
                                                                            class="badge bg-success-subtle text-success text-uppercase">Active</span>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('admin.' . $nameClass . '.status', ['uuid' => $item->uuid, 'status' => 1, 'name' => 'status']) }}"
                                                                        onclick="return confirm('Xác nhận kích hoạt {{ $nameItem }} ?')">
                                                                        <span
                                                                            class="badge bg-danger-subtle text-danger text-uppercase">Block</span>
                                                                    </a>
                                                                @endif
                                                            </td>

                                                            <td class="date">
                                                                {{ $item->updated_at ? $item->updated_at->format('d-m-Y') : $item->created_at->format('d-m-Y') }}
                                                            </td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    <div class="edit">
                                                                        <a href="{{ route('admin.' . $nameClass . '.edit', ['uuid' => $item->uuid]) }}"
                                                                            class="btn btn-sm btn-success edit-item-btn">Xem</a>
                                                                    </div>
                                                                    <div class="remove">
                                                                        <a href="{{ route('admin.' . $nameClass . '.destroy', ['uuid' => $item->uuid]) }}"
                                                                            class="btn btn-sm btn-danger remove-item-btn"
                                                                            onclick="return confirm('Xác nhận xóa {{ $nameItem }} ?')">Xóa</a>
                                                                    </div>
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
