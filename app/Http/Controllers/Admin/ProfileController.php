<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\ProfileRequest;
class ProfileController extends BaseController
{
    protected $model,$nameItem,$imageFolder;
    public function __construct($imageFolder = 'users')
    {
        $this->model = new User();
        $this->nameItem = 'Trang thông tin cá nhân';
        $this->imageFolder = $imageFolder;

        parent::__construct($imageFolder);

        View::share('nameClass', $imageFolder);
    }

    public function index(){
        return view('admin.modules.profile.index');
    }

    public function update($uuid,ProfileRequest $request){
        $data = $request->except('_token');
        $data['updated_at'] = new \DateTime();
        $admin = User::where('uuid',Auth::user()->uuid)->first();

        //avatar
        $data['avatar'] = $this->imageService->updateImage($request, $admin, $this->imageFolder, 'avatar', [
            'convertToWebp' => true,
            'quality' => 80,
            'mimeTypes' => ['image/jpeg', 'image/png','image/jpg', 'image/gif']
        ]);

        if ($admin) {
            $admin->update($data);
            toast('Cập nhật thông tin thành công ', 'success');
        } else {
            toast('admin not found', 'error');
        }
        return back();
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        // Kiểm tra mật khẩu cũ
        if (!Hash::check($request->old_password, $user->password)) {
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
