@extends('admin.layouts.app')

@section('title', 'Mail Configuration')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mail Configuration</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.mail-config.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Configuration
                        </a>
                    </div>
                </div>

                <div class="card-body">
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
                        <h5>Current Active Configuration:</h5>
                        @if($activeConfig)
                            <div class="alert alert-info">
                                <strong>Mailer:</strong> {{ $activeConfig['driver'] ?? 'N/A' }} |
                                <strong>Host:</strong> {{ $activeConfig['host'] ?? 'N/A' }} |
                                <strong>From:</strong> {{ $activeConfig['from']['address'] ?? 'N/A' }}
                            </div>
                        @else
                            <div class="alert alert-warning">
                                Using .env configuration (no active DB config found)
                            </div>
                        @endif
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mailer</th>
                                <th>Host</th>
                                <th>Port</th>
                                <th>From Address</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($configs as $config)
                                <tr>
                                    <td>{{ $config->id }}</td>
                                    <td>{{ $config->mailer }}</td>
                                    <td>{{ $config->host ?? 'N/A' }}</td>
                                    <td>{{ $config->port ?? 'N/A' }}</td>
                                    <td>{{ $config->from_address }}</td>
                                    <td>
                                        @if($config->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            @if(!$config->is_active)
                                                <form action="{{ route('admin.mail-config.set-active', $config->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="Set as Active">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <button type="button" class="btn btn-info btn-sm test-config" data-id="{{ $config->id }}" title="Test Configuration">
                                                <i class="fas fa-envelope"></i>
                                            </button>

                                            <a href="{{ route('admin.mail-config.edit', $config->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.mail-config.delete', $config->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this configuration?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No mail configurations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Result Modal -->
<div class="modal fade" id="testResultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Test Result</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="testResultContent">
                <!-- Test result will be displayed here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.test-config').on('click', function() {
        var configId = $(this).data('id');
        var button = $(this);

        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.post('{{ url("/admin/mail-config/test") }}/' + configId, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            var content = response.success
                ? '<div class="alert alert-success">' + response.message + '</div>'
                : '<div class="alert alert-danger">' + response.message + '</div>';

            $('#testResultContent').html(content);
            $('#testResultModal').modal('show');
        })
        .fail(function() {
            $('#testResultContent').html('<div class="alert alert-danger">Failed to test configuration. Please try again.</div>');
            $('#testResultModal').modal('show');
        })
        .always(function() {
            button.prop('disabled', false).html('<i class="fas fa-envelope"></i>');
        });
    });
});
</script>
@endsection