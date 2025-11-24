@extends('admin.layouts.app')

@section('title', 'Edit Mail Configuration')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Mail Configuration</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.mail-config.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.mail-config.update', $config->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mailer">Mailer Type <span class="text-danger">*</span></label>
                                    <select name="mailer" id="mailer" class="form-control @error('mailer') is-invalid @enderror" required>
                                        <option value="">Select Mailer</option>
                                        <option value="smtp" {{ old('mailer', $config->mailer) == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                        <option value="mailgun" {{ old('mailer', $config->mailer) == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                        <option value="ses" {{ old('mailer', $config->mailer) == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                        <option value="sendmail" {{ old('mailer', $config->mailer) == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    </select>
                                    @error('mailer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">Status</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $config->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Set as Active Configuration</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="smtp-fields" style="{{ $config->mailer == 'smtp' ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="host">SMTP Host <span class="text-danger">*</span></label>
                                        <input type="text" name="host" id="host" class="form-control @error('host') is-invalid @enderror"
                                               value="{{ old('host', $config->host) }}" placeholder="smtp.gmail.com">
                                        @error('host')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="port">SMTP Port <span class="text-danger">*</span></label>
                                        <input type="number" name="port" id="port" class="form-control @error('port') is-invalid @enderror"
                                               value="{{ old('port', $config->port) }}" min="1" max="65535">
                                        @error('port')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">SMTP Username</label>
                                        <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror"
                                               value="{{ old('username', $config->username) }}" placeholder="your-email@gmail.com">
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">SMTP Password</label>
                                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                               value="{{ old('password', $config->password) }}" placeholder="Your SMTP password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="encryption">Encryption</label>
                                        <select name="encryption" id="encryption" class="form-control @error('encryption') is-invalid @enderror">
                                            <option value="">None</option>
                                            <option value="tls" {{ old('encryption', $config->encryption) == 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ old('encryption', $config->encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        </select>
                                        @error('encryption')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_address">From Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="from_address" id="from_address" class="form-control @error('from_address') is-invalid @enderror"
                                           value="{{ old('from_address', $config->from_address) }}" placeholder="noreply@yourdomain.com" required>
                                    @error('from_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_name">From Name <span class="text-danger">*</span></label>
                                    <input type="text" name="from_name" id="from_name" class="form-control @error('from_name') is-invalid @enderror"
                                           value="{{ old('from_name', $config->from_name) }}" placeholder="Your App Name" required>
                                    @error('from_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Configuration
                        </button>
                        <a href="{{ route('admin.mail-config.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    function toggleSmtpFields() {
        var mailer = $('#mailer').val();
        if (mailer === 'smtp') {
            $('#smtp-fields').show();
            $('#host, #port').attr('required', true);
        } else {
            $('#smtp-fields').hide();
            $('#host, #port').removeAttr('required');
        }
    }

    $('#mailer').on('change', toggleSmtpFields);
    toggleSmtpFields(); // Initial check
});
</script>
@endsection