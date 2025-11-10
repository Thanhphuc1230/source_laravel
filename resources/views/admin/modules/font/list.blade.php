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
                                            <a type="button" href="{{ route('admin.' . $nameClass . '.create') }}"
                                                class="btn btn-success add-btn"><i
                                                    class="ri-add-line align-bottom me-1"></i> Thêm </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-auto">
                                        <a id="deleteSelectedItems" class="btn btn-danger add-btn">
                                            <i class="ri-delete-bin-5-line"></i> Xóa hết
                                        </a>
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
                                                <th><input type="checkbox" id="masterCheckbox"></th>
                                                <th class="sort">ID</th>
                                                <th class="sort">Tên Font</th>
                                                <th class="sort">Font Family</th>
                                                <th class="sort">Loại</th>
                                                <th class="sort">Trạng thái</th>
                                                <th class="sort">Thứ tự</th>
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
                                                            <td><input id="checkbox-data" class="form-check-input" type="checkbox"
                                                                    name="uuids[]" value="{{ $item->id }}"></td>
                                                            <td>{{ $item->id }}</td>
                                                            <td>
                                                                <span style="font-family: {{ $item->family }};">
                                                                    {{ $item->name }}
                                                                </span>
                                                            </td>
                                                            <td><code>{{ $item->family }}</code></td>
                                                            <td>
                                                                @if($item->type === 'system')
                                                                    <span class="badge badge-secondary">System</span>
                                                                @elseif($item->type === 'google')
                                                                    <span class="badge badge-info">Google</span>
                                                                @else
                                                                    <span class="badge badge-success">Upload</span>
                                                                @endif
                                                            </td>
                                                            <td class="status">
                                                                <div class="form-check form-switch form-switch-success mb-3">
                                                                    <input class="form-check-input status-checkbox" type="checkbox" role="switch" value="{{ $item->is_active }}" data-uuid="{{ $item->id }}" data-name="is_active" data-status="{{ $item->is_active }}" {{ $item->is_active == 1 ? 'checked' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control stt-input" value="{{ $item->sort_order }}" data-uuid="{{ $item->id }}" onchange="updateStt(this)" style="max-width: 45px;">
                                                            </td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    <div class="edit">
                                                                        <a href="{{ route('admin.' . $nameClass . '.edit', [$item->id, 'page' => $list->currentPage()]) }}"
                                                                            class="btn btn-sm btn-success edit-item-btn">Sửa</a>
                                                                    </div>
                                                                    <div class="remove">
                                                                        <a href="#" class="btn btn-sm btn-danger remove-item-btn" data-uuid="{{ $item->id }}" data-name="{{ $nameItem }}" data-bs-toggle="modal" data-bs-target="#deleteRecordModal">Xóa</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </form>
                                            @else
                                                <tr>
                                                    <td colspan="8" style="text-align:center">Chưa có dữ liệu</td>
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
    @include('admin.ajax.status')
    @include('admin.ajax.delete-modal')
    @include('admin.ajax.update-stt')
@endsection