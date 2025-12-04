@extends('admin.master')
@section('module', $nameItem)
@section('action', $action == 'create' ? 'Thêm' : 'Chỉnh sửa')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}
                            {{ $nameItem }}</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.mail-config.index') }}">Cấu hình mail</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} cấu hình mail
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <form
                action="{{ route('admin.mail-config.' . ($action == 'create' ? 'store' : 'update'), ['id' => $config->id ?? '']) }}"
                method="POST"
                autocomplete="off">
                @csrf
                @if($action == 'edit')
                    @method('PUT')
                @endif
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Thông tin cấu hình mail</h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <input type="hidden" name="mailer" value="smtp">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="host" class="form-label">SMTP Host <span class="text-danger">*</span></label>
                                            <input type="text" name="host" id="host" class="form-control @error('host') is-invalid @enderror"
                                                   value="{{ $action == 'edit' ? $config->host : old('host', 'smtp.gmail.com') }}" 
                                                   placeholder="smtp.gmail.com" required>
                                            @error('host')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="port" class="form-label">SMTP Port <span class="text-danger">*</span></label>
                                            <input type="number" name="port" id="port" class="form-control @error('port') is-invalid @enderror"
                                                   value="{{ $action == 'edit' ? $config->port : old('port', 587) }}" required>
                                            @error('port')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Email đăng nhập <span class="text-danger">*</span></label>
                                            <input type="email" name="username" id="username" class="form-control @error('username') is-invalid @enderror"
                                                   value="{{ $action == 'edit' ? $config->username : old('username') }}" 
                                                   placeholder="your-email@gmail.com" required>
                                            @error('username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">App Password @if($action == 'create')<span class="text-danger">*</span>@endif</label>
                                            <input type="password" name="password" id="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   placeholder="@if($action == 'edit')Để trống nếu không đổi@else Nhập App Password @endif"
                                                   autocomplete="new-password"
                                                   @if($action == 'create') required @endif>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            @if($action == 'edit')
                                                <small class="text-muted">Để trống để giữ nguyên mật khẩu</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="encryption" class="form-label">Mã hóa <span class="text-danger">*</span></label>
                                            <select name="encryption" id="encryption" class="form-control @error('encryption') is-invalid @enderror" required>
                                                <option value="tls" {{ ($action == 'edit' ? $config->encryption : old('encryption', 'tls')) == 'tls' ? 'selected' : '' }}>TLS (Port 587)</option>
                                                <option value="ssl" {{ ($action == 'edit' ? $config->encryption : old('encryption')) == 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                                            </select>
                                            @error('encryption')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="is_active" class="form-label">Trạng thái</label>
                                            <div class="form-check form-switch form-switch-success mt-2">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                                       {{ ($action == 'edit' ? $config->is_active : old('is_active', 1)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">Kích hoạt</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="from_address" class="form-label">Email gửi <span class="text-danger">*</span></label>
                                            <input type="email" name="from_address" id="from_address" class="form-control @error('from_address') is-invalid @enderror"
                                                   value="{{ $action == 'edit' ? $config->from_address : old('from_address') }}" required>
                                            @error('from_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="from_name" class="form-label">Tên người gửi <span class="text-danger">*</span></label>
                                            <input type="text" name="from_name" id="from_name" class="form-control @error('from_name') is-invalid @enderror"
                                                   value="{{ $action == 'edit' ? $config->from_name : old('from_name') }}" required>
                                            @error('from_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Hành động</h5>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> {{ $action == 'create' ? 'Thêm mới' : 'Cập nhật' }}
                                    </button>

                                    <a href="{{ route('admin.mail-config.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Quay lại
                                    </a>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div><!-- end row -->
            </form>
        </div>
    </div>
@endsection
