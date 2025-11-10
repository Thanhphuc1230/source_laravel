@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Chi tiết')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Thông tin bình luận</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{ route('admin.'.$nameClass.'.update', $page->uuid) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Tên người bình luận</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ old('name', $page->name ?? '') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="text" name="email" class="form-control"
                                                    value="{{ old('email', $page->email ?? '') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="id_post" class="form-label">ID Bài viết</label>
                                                <input type="text" name="id_post" class="form-control"
                                                    value="{{ old('id_post', $page->id_post ?? '') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="type_post" class="form-label">Loại bài viết</label>
                                                <input type="text" name="type_post" class="form-control"
                                                    value="{{ old('type_post', $page->type_post_name ?? '') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="content" class="form-label">Nội dung bình luận</label>
                                                <textarea class="form-control" name="content" rows="6" readonly>{{ old('content', $page->content ?? '') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Trạng thái duyệt</label>
                                                <select name="status" class="form-control">
                                                    <option value="1" {{ old('status', $page->status ?? '') == 1 ? 'selected' : '' }}>Đã duyệt</option>
                                                    <option value="0" {{ old('status', $page->status ?? '') == 0 ? 'selected' : '' }}>Chưa duyệt</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="created_at" class="form-label">Ngày tạo</label>
                                                <input type="text" name="created_at" class="form-control"
                                                    value="{{ $page->created_at ? $page->created_at->format('d-m-Y H:i:s') : '' }}" readonly>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <a href="{{ route('admin.'.$nameClass.'.index') }}" class="btn btn-primary me-2">Quay lại danh sách</a>
                                                <button type="submit" class="btn btn-success">Cập nhật</button>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
    </div>
@endsection