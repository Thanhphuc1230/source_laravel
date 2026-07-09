@extends('admin.master')
@section('module', 'System')
@section('action', 'Infomation')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <form action="{{ route('admin.system.update', ['id' => $system->id_system]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1"><strong>Thông tin website</strong></h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row">
                                        {{-- info website --}}
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Tên công ty</label>
                                                <input type="text" name="name_vn" class="form-control"
                                                    placeholder="Enter your name company"
                                                    value="{{ old('name_vn', $system->name_vn) }}">
                                                @error('name_vn')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Số điện thoại</label>
                                                <input type="text" name="phone" class="form-control"
                                                    placeholder="Enter your title page"
                                                    value="{{ old('phone', $system->phone) }}">
                                                @error('phone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Địa chỉ VN</label>
                                                <input class="form-control" name="address" placeholder="Enter your message"
                                                    value="{{ old('address', $system->address) }}">
                                                @error('address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Email</label>
                                                <input type="text" name="email" class="form-control"
                                                    placeholder="Enter your title page"
                                                    value="{{ old('email', $system->email) }}">
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Email nhận thông báo</label>
                                                <input type="text" name="email_alert" class="form-control"
                                                    placeholder="Enter your title page"
                                                    value="{{ old('email_alert', $system->email_alert) }}">
                                                @error('email_alert')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!--end col-->
                                        <div class="col-md-6">
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phonenumberInput" class="form-label">Footer VN</label>
                                                <textarea class="form-control" name="footer_vn" id="content-vn" rows="6" placeholder="Enter your message">{{ old('footer_vn', $system->footer_vn) }}</textarea>
                                                @error('footer_vn')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1"><strong>Mạng xã hội</strong></h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row">
                                        {{-- Social media --}}
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Facebook</label>
                                                <input type="text" name="facebook" class="form-control"
                                                    placeholder="Link to Facebook"
                                                    value="{{ old('facebook', $system->facebook) }}">
                                                @error('facebook')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Twitter</label>
                                                <input type="text" name="twitter" class="form-control"
                                                    placeholder="Link to Twitter"
                                                    value="{{ old('twitter', $system->twitter) }}">
                                                @error('twitter')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Youtube</label>
                                                <input type="text" name="youtube" class="form-control"
                                                    placeholder="Link to Youtube"
                                                    value="{{ old('youtube', $system->youtube) }}">
                                                @error('youtube')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Instagram</label>
                                                <input type="text" name="instagram" class="form-control"
                                                    placeholder="Link to Instagram"
                                                    value="{{ old('instagram', $system->instagram) }}">
                                                @error('instagram')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Zalo</label>
                                                <input type="text" name="zalo" class="form-control"
                                                    placeholder="Link to Zalo" value="{{ old('zalo', $system->zalo) }}">
                                                @error('zalo')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <!--end row-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1"><strong>Hình ảnh</strong></h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Favicon</label>
                                                <img src="{{ $system->favicon }}" alt="favicon"
                                                    width="48px">
                                                @error('favicon')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Logo(200x100)</label>
                                                <img src="{{ $system->logo }}" alt="logo"
                                                    width="150px">
                                                @error('logo')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Favicon</label>
                                                <input type="file" name="favicon" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Logo</label>
                                                <input type="file" name="logo" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <!--end row-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1"><strong>Nội dung Seo</strong></h4>
                            </div><!-- end card header -->

                            <div class="card-body">
                                <div class="live-preview">

                                    <div class="row">
                                        <!--end col-->
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="lastNameinput" class="form-label">Từ khóa</label>
                                                <textarea class="form-control" name="keyword" rows="3" placeholder="Enter your message">{{ old('keyword', $system->keyword) }}</textarea>
                                                @error('keyword')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="compnayNameinput" class="form-label">Mô tả</label>
                                                <textarea class="form-control" name="description" rows="3" placeholder="Enter your message">{{ old('description', $system->description) }}</textarea>
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror   
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="compnayNameinput" class="form-label">Iframe Map</label>
                                                <textarea class="form-control" name="map" rows="3" placeholder="Enter your message">{{ old('map', $system->map) }}</textarea>
                                                @error('map')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">Lưu</button>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
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
