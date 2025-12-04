@extends('admin.master')

@section('title', 'Quản lý File')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quản lý File</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" id="uploadBtn">
                            <i class="fas fa-upload"></i> Upload File
                        </button>
                        <button type="button" class="btn btn-success btn-sm" id="createFolderBtn">
                            <i class="fas fa-folder-plus"></i> Tạo Thư Mục
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" id="breadcrumbNav">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" data-path="">Root</a></li>
                        </ol>
                    </nav>

                    <!-- File List -->
                    <div class="row" id="fileList">
                        @foreach($files as $file)
                            <div class="col-md-2 col-sm-4 col-6 mb-3">
                                <div class="card file-item" data-type="{{ $file['type'] }}" data-name="{{ $file['name'] }}" data-path="{{ $file['path'] }}">
                                    <div class="card-body text-center p-2">
                                        @if($file['type'] === 'directory')
                                            <i class="fas fa-folder fa-3x text-warning"></i>
                                            <p class="mb-1 mt-2">{{ $file['name'] }}</p>
                                        @else
                                            @if($file['is_image'])
                                                <img src="{{ $file['url'] }}" class="img-thumbnail" style="max-height: 80px;" alt="{{ $file['name'] }}">
                                            @else
                                                <i class="fas fa-file fa-3x text-secondary"></i>
                                            @endif
                                            <p class="mb-1 mt-2 small">{{ $file['name'] }}</p>
                                            <small class="text-muted">{{ number_format($file['size'] / 1024, 1) }} KB</small>
                                        @endif
                                    </div>
                                    <div class="card-footer p-1">
                                        <div class="btn-group btn-group-sm w-100">
                                            @if($file['type'] === 'directory')
                                                <button class="btn btn-outline-primary btn-sm open-folder">
                                                    <i class="fas fa-folder-open"></i>
                                                </button>
                                            @else
                                                <a href="{{ $file['url'] }}" target="_blank" class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                            <button class="btn btn-outline-danger btn-sm delete-file">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload File</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="fileInput">Chọn file</label>
                        <input type="file" class="form-control-file" id="fileInput" name="files[]" multiple required>
                        <small class="form-text text-muted">Cho phép: JPG, PNG, GIF, WebP, SVG, PDF, DOC, DOCX, XLS, XLSX. Tối đa 10MB mỗi file.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Folder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo Thư Mục Mới</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="createFolderForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="folderName">Tên thư mục</label>
                        <input type="text" class="form-control" id="folderName" name="name" required pattern="[a-zA-Z0-9_-]+" title="Chỉ cho phép chữ cái, số, gạch dưới và gạch ngang">
                        <small class="form-text text-muted">Chỉ cho phép chữ cái, số, gạch dưới (_) và gạch ngang (-).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Tạo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let currentPath = '{{ $currentPath }}';

    // Update breadcrumb
    function updateBreadcrumb(path) {
        const parts = path.split('/').filter(p => p);
        let breadcrumbHtml = '<li class="breadcrumb-item"><a href="#" data-path="">Root</a></li>';

        let currentPath = '';
        parts.forEach((part, index) => {
            currentPath += (currentPath ? '/' : '') + part;
            breadcrumbHtml += `<li class="breadcrumb-item"><a href="#" data-path="${currentPath}">${part}</a></li>`;
        });

        $('#breadcrumbNav .breadcrumb').html(breadcrumbHtml);
    }

    // Load files
    function loadFiles(path) {
        currentPath = path;
        updateBreadcrumb(path);

        $.get('{{ route("filemanager.index") }}', { path: path })
            .done(function(response) {
                // Update file list - this would need to be implemented as a partial view
                location.reload(); // Temporary solution
            });
    }

    // Upload files
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('path', currentPath);

        $.ajax({
            url: '{{ route("filemanager.upload") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#uploadModal').modal('hide');
                    toastr.success(response.message);
                    loadFiles(currentPath);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Upload thất bại');
            }
        });
    });

    // Create folder
    $('#createFolderForm').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('path', currentPath);

        $.ajax({
            url: '{{ route("filemanager.create-folder") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#createFolderModal').modal('hide');
                    $('#createFolderForm')[0].reset();
                    toastr.success(response.message);
                    loadFiles(currentPath);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Tạo thư mục thất bại');
            }
        });
    });

    // Delete file
    $(document).on('click', '.delete-file', function() {
        const $card = $(this).closest('.file-item');
        const fileName = $card.data('name');
        const filePath = $card.data('path');

        if (confirm(`Bạn có chắc muốn xóa "${fileName}"?`)) {
            $.ajax({
                url: '{{ route("filemanager.delete") }}',
                type: 'DELETE',
                data: {
                    path: currentPath,
                    filename: fileName,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadFiles(currentPath);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Xóa file thất bại');
                }
            });
        }
    });

    // Open folder
    $(document).on('click', '.open-folder', function() {
        const $card = $(this).closest('.file-item');
        const folderName = $card.data('name');
        const newPath = currentPath ? currentPath + '/' + folderName : folderName;
        loadFiles(newPath);
    });

    // Breadcrumb navigation
    $(document).on('click', '#breadcrumbNav a', function(e) {
        e.preventDefault();
        const path = $(this).data('path');
        loadFiles(path);
    });

    // Modal triggers
    $('#uploadBtn').click(function() {
        $('#uploadModal').modal('show');
    });

    $('#createFolderBtn').click(function() {
        $('#createFolderModal').modal('show');
    });

    // Initialize breadcrumb
    updateBreadcrumb(currentPath);
});
</script>
@endsection