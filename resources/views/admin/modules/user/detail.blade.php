@extends('admin.master')
@section('module', $nameItem)
@section('action', $action == 'create' ? 'Thêm' : 'Chỉnh sửa')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }} {{ $nameItem }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Người dùng</a></li>
                                <li class="breadcrumb-item active">{{ $action == 'create' ? 'Thêm' : 'Chỉnh sửa' }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <form action="{{ route('admin.user.' . ($action == 'create' ? 'store' : 'update'), ['uuid' => $user->uuid ?? '']) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if($action == 'edit')
                    @method('PUT')
                @endif
                
                <div class="row">
                    <!-- Left Column: User Info -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0 text-dark">Thông tin người dùng</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="fullname">Họ và tên <span class="text-danger">*</span></label>
                                            <input type="text" id="fullname"
                                                class="form-control @error('fullname') is-invalid @enderror" name="fullname"
                                                value="{{ old('fullname', $user->fullname ?? '') }}"
                                                placeholder="Nhập họ và tên" required>
                                            @error('fullname')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="username">Tên đăng nhập <span class="text-danger">*</span></label>
                                            <input type="text" id="username"
                                                class="form-control @error('username') is-invalid @enderror" name="username"
                                                value="{{ old('username', $user->username ?? '') }}"
                                                placeholder="Nhập tên đăng nhập" required>
                                            @error('username')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" id="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email', $user->email ?? '') }}"
                                                placeholder="Nhập email" required>
                                            @error('email')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="phone">Số điện thoại</label>
                                            <input type="tel" id="phone"
                                                class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                value="{{ old('phone', $user->phone ?? '') }}"
                                                placeholder="0xxxxxxxxx hoặc +84xxxxxxxxx">
                                            @error('phone')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if($action == 'create')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="password">Mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                placeholder="Nhập mật khẩu" required>
                                            @error('password')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="password_confirmation">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                            <input type="password" id="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation"
                                                placeholder="Nhập lại mật khẩu" required>
                                            @error('password_confirmation')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="level">Cấp độ <span class="text-danger">*</span></label>
                                            <select id="level" name="level" class="form-select @error('level') is-invalid @enderror" required>
                                                <option value="">Chọn cấp độ</option>
                                                <option value="1" {{ old('level', $user->level ?? '') == 1 ? 'selected' : '' }}>Quản trị viên (Level 1)</option>
                                                <option value="2" {{ old('level', $user->level ?? '') == 2 ? 'selected' : '' }}>Quản lý (Level 2)</option>
                                                <option value="3" {{ old('level', $user->level ?? '') == 3 ? 'selected' : '' }}>Nhân viên (Level 3)</option>
                                                <option value="4" {{ old('level', $user->level ?? '') == 4 ? 'selected' : '' }}>Người xem (Level 4)</option>
                                            </select>
                                            @error('level')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold" for="status">Trạng thái <span class="text-danger">*</span></label>
                                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                                <option value="">Chọn trạng thái</option>
                                                <option value="1" {{ old('status', isset($user) ? (string)$user->status : '') === '1' ? 'selected' : '' }}>Hoạt động</option>
                                                <option value="0" {{ old('status', isset($user) ? (string)$user->status : '') === '0' ? 'selected' : '' }}>Không hoạt động</option>
                                            </select>
                                            @error('status')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex justify-content-end gap-2 border-top pt-3">
                                    <a href="{{ route('admin.user.index') }}" class="btn btn-light">Hủy</a>
                                    <button type="submit" class="btn btn-primary">{{ $action == 'create' ? 'Tạo mới' : 'Cập nhật' }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Role assignment -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0 text-dark">Phân quyền vai trò</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold mb-2">Chọn vai trò cho tài khoản này:</label>
                                    <div class="roles-selection-list" style="max-height: 350px; overflow-y: auto;">
                                        @foreach($roles as $role)
                                        <div class="form-check form-check-primary mb-2 p-2 rounded border border-dashed transition-all hover-shadow-sm">
                                            <input class="form-check-input ms-1 me-2" type="checkbox" name="roles[]" value="{{ $role->id }}"
                                                id="role-{{ $role->id }}"
                                                {{ in_array($role->id, old('roles', $userRoles ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label ps-4 fw-medium text-dark" for="role-{{ $role->id }}" style="cursor: pointer; width: 100%;">
                                                {{ $role->display_name }}
                                                <span class="d-block text-muted" style="font-size: 0.75em;">{{ $role->name }}</span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('roles')
                                        <span class="text-danger small d-block mt-2">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .hover-shadow-sm:hover {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        border-color: #adb5bd !important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
</style>
@endpush