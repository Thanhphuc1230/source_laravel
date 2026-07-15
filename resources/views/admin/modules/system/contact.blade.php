@extends('admin.master')
@section('module', 'System')
@section('action', 'Contact Configuration')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <form action="{{ route('admin.system.updateContact', ['id' => $system->id_system]) }}" method="POST">
                    @csrf
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1"><strong>Cấu hình trang liên hệ</strong></h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row">
                                        {{-- Tiêu đề liên hệ VN --}}
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Tiêu đề liên hệ (VN)</label>
                                                <input type="text" name="contact_title_vn" class="form-control"
                                                    placeholder="Nhập tiêu đề hiển thị trang liên hệ"
                                                    value="{{ old('contact_title_vn', $system->contact_title_vn) }}">
                                                @error('contact_title_vn')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Mô tả liên hệ VN --}}
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Mô tả chi tiết liên hệ (VN)</label>
                                                <textarea class="form-control" name="contact_desc_vn" id="content-vn" rows="6" placeholder="Nhập đoạn mô tả chi tiết liên hệ">{{ old('contact_desc_vn', $system->contact_desc_vn) }}</textarea>
                                                @error('contact_desc_vn')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        {{-- Tiêu đề liên hệ EN --}}
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Tiêu đề liên hệ (EN)</label>
                                                <input type="text" name="contact_title_en" class="form-control"
                                                    placeholder="Enter contact title for English page"
                                                    value="{{ old('contact_title_en', $system->contact_title_en) }}">
                                                @error('contact_title_en')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Mô tả liên hệ EN --}}
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Mô tả chi tiết liên hệ (EN)</label>
                                                <textarea class="form-control" name="contact_desc_en" id="content-en" rows="6" placeholder="Enter contact description for English page">{{ old('contact_desc_en', $system->contact_desc_en) }}</textarea>
                                                @error('contact_desc_en')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Submit --}}
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">Lưu cấu hình</button>
                                            </div>
                                        </div>
                                    </div><!-- end row -->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.partials.ckeditor')
@endsection
