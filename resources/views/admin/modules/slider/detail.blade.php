@extends('admin.master')
@section('module', $nameItem)
@section('action', 'Add')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}
                                {{ $nameItem }}</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="live-preview">
                                <form
                                    action="{{ route('admin.' . $nameClass . ($action == 'create' ? '.store' : '.update'), ['uuid' => $page->uuid ?? '']) }}"
                                    method="POST" enctype="multipart/form-data">

                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Tiêu đề
                                                    {{ $nameItem }} Tiếng việt</label>
                                                <input type="text" name="name_vn"
                                                    class="form-control @error('name_vn') is-invalid @enderror"
                                                    placeholder="Enter your title page"
                                                    value="{{ old('name_vn', $page->name_vn ?? '') }}">
                                                @error('name_vn')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Đường dẫn chuyển trang
                                                </label>
                                                <input type="text" name="link"
                                                    class="form-control @error('link') is-invalid @enderror"
                                                    value="{{ old('link', $page->link ?? '') }}">
                                                @error('link')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="compnayNameinput" class="form-label">Số thứ tự</label>
                                                <input type="number" name="stt"
                                                    class="form-control @error('stt') is-invalid @enderror"
                                                    placeholder="Enter your title page"
                                                    value="{{ old('stt', $page->stt ?? '') }}">
                                                @error('stt')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="created_at" class="form-label">Ngày đăng</label>
                                                <input type="datetime-local" id="created_at" name="created_at"
                                                    class="form-control @error('created_at') is-invalid @enderror"
                                                    value="{{ old('created_at', isset($page->created_at) ? \Carbon\Carbon::parse($page->created_at)->format('Y-m-d\TH:i') : '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Hình ảnh</label>
                                                <input type="file" name="image"
                                                    class="form-control @error('image') is-invalid @enderror">
                                                @error('image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        @if (!empty($page->image))
                                            <div class="col-md-6">
                                                <div class="mb-3" style="display:flex;flex-direction: column;">
                                                    <label for="firstNameinput" class="form-label">Hình ảnh hiện tại</label>
                                                     <img src="{{ $page->image }}" alt=""
                                                         width="200px" height="auto">
                                                </div>
                                            </div>
                                        @endif
                                        <!--end col-->
                                        @if ($action == 'create')
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <input type="submit" name="return_back" class="btn btn-primary"
                                                        value="Lưu và tạo mới">
                                                    <input type="submit" name="return_list" class="btn btn-primary"
                                                        value="Lưu và về danh sách">
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <input type="submit" name="return_list" class="btn btn-primary"
                                                        value="Cập nhật">
                                                </div>
                                            </div>
                                        @endif
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
    @include('admin.partials.ckeditor')
@endsection
