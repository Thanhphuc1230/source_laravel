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
                            <h4 class="card-title mb-0 flex-grow-1">Thông tin liên hệ</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="live-preview">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Họ và tên</label>
                                                <input type="text" name="fullname" class="form-control"
                                                    placeholder="Enter your title page"
                                                    value="{{ old('fullname', $page->fullname ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Email</label>
                                                <input type="text" name="email" class="form-control"
                                                    placeholder="Enter your title page"
                                                    value="{{ old('email', $page->email ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">SĐT</label>
                                                <input type="text" name="phone" class="form-control"
                                                    placeholder="Enter your title page"
                                                    value="{{ old('phone', $page->phone ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="firstNameinput" class="form-label">Tiêu đề</label>
                                                <input type="text" name="subject" class="form-control"
                                                    placeholder="Enter your title page"
                                                    value="{{ old('subject', $page->subject ?? '') }}">
                                            </div>
                                        </div>
                                   
                                       
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="phonenumberInput" class="form-label">Nội dung </label>
                                                <textarea  class="form-control" name="message" rows="6" placeholder="Enter your message">{{ old('message', $page->message ?? '') }}</textarea>
                                            </div>
                                        </div>
                                        <!--end col-->
                                     
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <form action="{{ route('admin.'. $nameClass .'.index') }}" method="GET">
                                                    <button type="submit" class="btn btn-primary">Quay lại danh sách</button>
                                                </form>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        </div>
    </div>
@endsection
