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
                                    @hasPermission('mail-config.create')
                                        <div class="col-sm-auto">
                                            <div>
                                                <a type="button" href="{{ route('admin.mail-config.create') }}"
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

                                <div class="mb-3">
                                    <h5>Cấu hình mail đang hoạt động:</h5>
                                    @if($activeConfig)
                                        <div class="alert alert-info">
                                            <strong>Mailer:</strong> {{ $activeConfig['driver'] ?? 'N/A' }} |
                                            <strong>Host:</strong> {{ $activeConfig['host'] ?? 'N/A' }} |
                                            <strong>From:</strong> {{ $activeConfig['from']['address'] ?? 'N/A' }}
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            Đang sử dụng cấu hình .env (không có cấu hình DB nào đang hoạt động)
                                        </div>
                                    @endif
                                </div>

                                <div class="table-responsive table-card mt-3 mb-1">
                                    <table class="table align-middle table-nowrap" id="customerTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="sort">ID</th>
                                                <th class="sort">Mailer</th>
                                                <th class="sort">Host</th>
                                                <th class="sort">Port</th>
                                                <th class="sort">Email gửi</th>
                                                <th class="sort">Trạng thái</th>
                                                <th class="sort">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if (count($configs) > 0)
                                                @foreach ($configs as $config)
                                                    <tr>
                                                        <td>{{ $config->id }}</td>
                                                        <td>{{ $config->mailer }}</td>
                                                        <td>{{ $config->host ?? 'N/A' }}</td>
                                                        <td>{{ $config->port ?? 'N/A' }}</td>
                                                        <td>{{ $config->from_address }}</td>
                                                        <td>
                                                            @if($config->is_active)
                                                                <span class="badge bg-success">Hoạt động</span>
                                                            @else
                                                                <span class="badge bg-secondary">Không hoạt động</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                @hasPermission('mail-config.edit')
                                                                    <div class="edit">
                                                                        <a href="{{ route('admin.mail-config.edit', $config->id) }}"
                                                                            class="btn btn-sm btn-success edit-item-btn">Sửa</a>
                                                                    </div>
                                                                @endhasPermission
                                                                @hasPermission('mail-config.delete')
                                                                    <div class="remove">
                                                                        <a href="{{ route('admin.mail-config.delete', $config->id) }}"
                                                                            class="btn btn-sm btn-danger remove-item-btn"
                                                                            onclick="return confirm('Xác nhận xóa {{ $nameItem }} ?')">Xóa</a>
                                                                    </div>
                                                                @endhasPermission
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" style="text-align:center">Chưa có dữ liệu</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- end card -->
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
        @include('admin.ajax.status')
        @include('admin.ajax.update-stt')
    @endsection