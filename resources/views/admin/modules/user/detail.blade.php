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
                                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Người dùng</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} người dùng
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <form
                action="{{ route('admin.user.' . ($action == 'create' ? 'store' : 'update'), ['uuid' => $user->uuid ?? '']) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if($action == 'edit')
                    @method('PUT')
                @endif
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label" for="fullname">Họ và tên</label>
                                    <input type="text" id="fullname"
                                        class="form-control @error('fullname') is-invalid @enderror" name="fullname"
                                        value="{{ old('fullname', $user->fullname ?? '') }}"
                                        placeholder="Nhập họ và tên">
                                    @error('fullname')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="username">Tên đăng nhập</label>
                                    <input type="text" id="username"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username', $user->username ?? '') }}"
                                        placeholder="Nhập tên đăng nhập">
                                    @error('username')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email', $user->email ?? '') }}"
                                        placeholder="Nhập email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="phone">Số điện thoại</label>
                                    <input type="tel" id="phone"
                                        class="form-control @error('phone') is-invalid @enderror" name="phone"
                                        value="{{ old('phone', $user->phone ?? '') }}"
                                        placeholder="0xxxxxxxxx hoặc +84xxxxxxxxx"
                                        pattern="^(?:\+84|0)\d{9}$">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if($action == 'create')
                                <div class="mb-3">
                                    <label class="form-label" for="password">Mật khẩu</label>
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="Nhập mật khẩu">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="password_confirmation">Xác nhận mật khẩu</label>
                                    <input type="password" id="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation"
                                        placeholder="Nhập lại mật khẩu">
                                    @error('password_confirmation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label" for="level">Cấp độ</label>
                                    <select id="level" name="level" class="form-select @error('level') is-invalid @enderror">
                                        <option value="">Chọn cấp độ</option>
                                        <option value="1" {{ old('level', $user->level ?? '') == 1 ? 'selected' : '' }}>Quản trị viên</option>
                                        <option value="2" {{ old('level', $user->level ?? '') == 2 ? 'selected' : '' }}>Quản lý</option>
                                        <option value="3" {{ old('level', $user->level ?? '') == 3 ? 'selected' : '' }}>Người dùng</option>
                                    </select>
                                    @error('level')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" for="status">Trạng thái</label>
                                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="">Chọn trạng thái</option>
                                        <option value="1" {{ old('status', $user->status ?? '') == 1 ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="0" {{ old('status', $user->status ?? '') == 0 ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Phân quyền</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Vai trò</label>
                                    <div class="form-check" style="max-height: 200px; overflow-y: auto;">
                                        @foreach($roles as $role)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}"
                                                id="role-{{ $role->id }}"
                                                {{ in_array($role->id, old('roles', $userRoles ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role-{{ $role->id }}">
                                                {{ $role->display_name }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('roles')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('roles.*')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Hủy</a>
                                    <button type="submit" class="btn btn-primary">{{ $action == 'create' ? 'Tạo mới' : 'Cập nhật' }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection