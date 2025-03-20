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
                                                <form action="{{ route('admin.' . $nameClass . '.index') }}" method="get"
                                                    style="display: flex">
                                                    @csrf
                                                    <select class="form-select mb-3" name="category">
                                                        <option value="0" selected>Chọn chủ đề </option>
                                                        @foreach ($category as $item)
                                                            <option value="{{ $item->id_category_new }}">
                                                                {{ $item->name_vn }}
                                                            </option>
                                                            @if ($item->children)
                                                                @foreach ($item->children as $child)
                                                                    <option value="{{ $child->id_category_new }}">
                                                                        |---{{ $child->name_vn }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <input type="text" class="form-control search" name="search"
                                                        placeholder="Search..." style="height: 37.5px">
                                                    <button type="submit"
                                                        class="btn btn-success w-lg waves-effect waves-light"
                                                        style="height: 37.5px">Search</button>
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
                                                <th class="sort">Tiêu đề</th>
                                                <th class="sort">Hiển thị</th>
                                                <th class="sort">Trang chủ</th>
                                                <th class="sort">STT</th>
                                                <th class="sort">Ngày cập nhật</th>
                                                <th class="sort">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($list) > 0)
                                                <form id="delete-form-all"
                                                    action="{{ route('admin.' . $nameClass . '.destroyAll') }}"
                                                    method="POST">
                                                    @csrf
                                                    @foreach ($list as $item)
                                                        <tr>
                                                            <td><input class="form-check-input" id="checkbox-data"
                                                                    type="checkbox" name="uuids[]"
                                                                    value="{{ $item->uuid }}"></td>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $item->name_vn }}</td>
                                                            <td class="status">
                                                                <div
                                                                    class="form-check form-switch form-switch-success mb-3">
                                                                    <input class="form-check-input status-checkbox"
                                                                        type="checkbox" role="switch"
                                                                        value="{{ $item->status }}"
                                                                        data-uuid="{{ $item->uuid }}" data-name="status"
                                                                        data-status="{{ $item->status }}"
                                                                        {{ $item->status == 1 ? 'checked' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td class="home">
                                                                <div
                                                                    class="form-check form-switch form-switch-success mb-3">
                                                                    <input class="form-check-input status-checkbox"
                                                                        type="checkbox" role="switch"
                                                                        value="{{ $item->home }}"
                                                                        data-uuid="{{ $item->uuid }}" data-name="home"
                                                                        data-status="{{ $item->home }}"
                                                                        {{ $item->home == 1 ? 'checked' : '' }}>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control stt-input"
                                                                    value="{{ $item->stt }}"
                                                                    data-uuid="{{ $item->uuid }}"
                                                                    onchange="updateStt(this)" style="max-width: 45px;">
                                                            </td>
                                                            <td class="date">
                                                                {{ $item->updated_at ? $item->updated_at->format('d-m-Y') : $item->created_at->format('d-m-Y') }}
                                                            </td>

                                                            </td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    <div class="edit">
                                                                        <a href="{{ route('admin.' . $nameClass . '.edit', ['uuid' => $item->uuid, 'page' => $list->currentPage()]) }}"
                                                                            class="btn btn-sm btn-success edit-item-btn">Sửa</a>
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
                                                    <td colspan="7" style="text-align:center">Chưa có dữ liệu</td>
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
                </div>
            </div>
            <!-- container-fluid -->
        </div>
    @endsection
