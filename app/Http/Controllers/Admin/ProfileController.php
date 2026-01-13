<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class ProfileController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $imageFolder;

    public function __construct($imageFolder = 'users')
    {
        $this->module = 'profile';
        $this->model = new User;
        $this->nameItem = 'Trang thông tin cá nhân';
        $this->imageFolder = $imageFolder;

        parent::__construct($this->module, $imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index()
    {
        return view('admin.modules.profile.index');
    }

    public function update($uuid, ProfileRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            toast('Phiên đăng nhập hết hạn', 'error');
            return redirect()->route('getLogin');
        }

        $admin = User::where('uuid', $user->uuid)->first();
        if (!$admin) {
            toast('Không tìm thấy người dùng', 'error');
            return back();
        }

        $data = $request->except('_token', 'updated_at');
        
        // Handle avatar - Update existing avatar
        $avatarPath = $this->updateImage($request, $admin, null, 'avatar');
        if ($avatarPath) {
            $data['avatar'] = $avatarPath;
        }

        $admin->update($data);
        toast('Cập nhật thông tin thành công', 'success');

        return back();
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        // Kiểm tra mật khẩu cũ
        if (! Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Mật khẩu cũ không đúng');
        }

        // Kiểm tra mật khẩu mới và mật khẩu xác nhận
        if ($request->new_password !== $request->confirm_password) {
            return back()->with('error', 'Mật khẩu mới và xác thực mật khẩu không giống nhau');
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);

        if ($user instanceof \App\Models\User && $user->save()) { // Ensure $user is a User model
            toast('Đổi mật khẩu thành công', 'success');
        } else {
            toast('Đổi mật khẩu không thành công', 'error');
        }

        return back();
    }
}
