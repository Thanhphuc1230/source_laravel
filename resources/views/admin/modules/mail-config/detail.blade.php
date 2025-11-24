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
                method="POST">
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="mailer" class="form-label">Loại mailer <span class="text-danger">*</span></label>
                                            <select name="mailer" id="mailer" class="form-control @error('mailer') is-invalid @enderror" required>
                                                <option value="">Chọn loại mailer</option>
                                                <option value="smtp" {{ ($action == 'edit' ? $config->mailer : old('mailer')) == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                                <option value="mailgun" {{ ($action == 'edit' ? $config->mailer : old('mailer')) == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                                <option value="ses" {{ ($action == 'edit' ? $config->mailer : old('mailer')) == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                                <option value="sendmail" {{ ($action == 'edit' ? $config->mailer : old('mailer')) == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                            </select>
                                            @error('mailer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="is_active" class="form-label">Trạng thái</label>
                                            <div class="form-check form-switch form-switch-success">
                                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                                                       {{ ($action == 'edit' ? $config->is_active : old('is_active')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">Đặt làm cấu hình hoạt động</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="smtp-fields" style="{{ ($action == 'edit' ? $config->mailer : old('mailer')) == 'smtp' ? '' : 'display: none;' }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="host" class="form-label">Host <span class="text-danger">*</span></label>
                                                <input type="text" name="host" id="host" class="form-control @error('host') is-invalid @enderror"
                                                       value="{{ $action == 'edit' ? $config->host : old('host') }}" required>
                                                @error('host')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="port" class="form-label">Port <span class="text-danger">*</span></label>
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
                                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror"
                                                       value="{{ $action == 'edit' ? $config->username : old('username') }}" required>
                                                @error('username')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                                       value="{{ $action == 'edit' ? $config->password : old('password') }}" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="encryption" class="form-label">Mã hóa</label>
                                                <select name="encryption" id="encryption" class="form-control">
                                                    <option value="">Không mã hóa</option>
                                                    <option value="tls" {{ ($action == 'edit' ? $config->encryption : old('encryption')) == 'tls' ? 'selected' : '' }}>TLS</option>
                                                    <option value="ssl" {{ ($action == 'edit' ? $config->encryption : old('encryption')) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="mailgun-fields" style="{{ ($action == 'edit' ? $config->mailer : old('mailer')) == 'mailgun' ? '' : 'display: none;' }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="host" class="form-label">Host</label>
                                                <input type="text" name="host" id="mailgun-host" class="form-control"
                                                       value="{{ $action == 'edit' ? $config->host : old('host') }}" placeholder="smtp.mailgun.org">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="port" class="form-label">Port</label>
                                                <input type="number" name="port" id="mailgun-port" class="form-control"
                                                       value="{{ $action == 'edit' ? $config->port : old('port', 587) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                                <input type="text" name="username" id="mailgun-username" class="form-control @error('username') is-invalid @enderror"
                                                       value="{{ $action == 'edit' ? $config->username : old('username') }}" required>
                                                @error('username')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                                <input type="password" name="password" id="mailgun-password" class="form-control @error('password') is-invalid @enderror"
                                                       value="{{ $action == 'edit' ? $config->password : old('password') }}" required>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
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

@section('scripts')
<script>
$(document).ready(function() {
    // Toggle fields based on mailer type
    $('#mailer').on('change', function() {
        var mailer = $(this).val();

        // Hide all field groups
        $('#smtp-fields, #mailgun-fields').hide();

        // Show relevant fields and update requirements
        if (mailer === 'smtp') {
            $('#smtp-fields').show();
            $('#host, #port, #username, #password').prop('required', true);
        } else if (mailer === 'mailgun') {
            $('#mailgun-fields').show();
            $('#host, #port').prop('required', false);
            $('#username, #password').prop('required', true);
        } else if (mailer === 'ses') {
            $('#username, #password').prop('required', true);
            $('#host, #port').prop('required', false);
        } else if (mailer === 'sendmail') {
            $('#host, #port, #username, #password').prop('required', false);
        }
    });

    // Trigger change on page load to show correct fields
    $('#mailer').trigger('change');
});
</script>
@endsection