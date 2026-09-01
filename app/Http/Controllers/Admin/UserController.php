<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class UserController extends BaseController
{
    protected $module;

    protected $model;

    protected $nameItem;

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->module = 'user';
        $this->model = new User;
        $this->nameItem = 'người dùng';
        $this->userRepository = $userRepository;

        parent::__construct($this->module);

        View::share('nameClass', $this->module);
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'level' => $request->input('level'),
            'status' => $request->input('status'),
            'sort_field' => $request->input('sort_field', 'created_at'),
            'sort_direction' => $request->input('sort_direction', 'desc')
        ];

        $data['list'] = $this->userRepository->getFilteredUsers($filters);
        $data['nameItem'] = $this->nameItem;

        return $this->view_admin('list', $data);
    }

    public function create()
    {
        $data['nameItem'] = $this->nameItem;
        $data['action'] = 'create';
        $data['roles'] = $this->userRepository->getAllRoles();
        $data['userRoles'] = [];

        return $this->view_admin('detail', $data);
    }

    public function edit($uuid)
    {
        $user = $this->userRepository->getUserWithRolesByUuid($uuid);

        if (!$user) {
            toast('Người dùng không tồn tại!', 'error');
            return redirect()->route('admin.user.index');
        }

        $data['nameItem'] = $this->nameItem;
        $data['action'] = 'edit';
        $data['user'] = $user;
        $data['roles'] = $this->userRepository->getAllRoles();
        $data['userRoles'] = $user->roles->pluck('id')->toArray();

        return $this->view_admin('detail', $data);
    }

    public function update(UserRequest $request, $uuid)
    {
        $data = $request->validated();

        try {
            $this->userRepository->updateUser($data, $uuid);

            toast('Cập nhật người dùng thành công!', 'success');
            return redirect()->route('admin.user.index');
        } catch (\Exception $e) {
            toast('Có lỗi xảy ra khi cập nhật người dùng!', 'error');
            return back()->withInput();
        }
    }

    public function destroy($uuid)
    {
        $user = $this->userRepository->findByUuid($uuid);

        if (!$user) {
            toast('Người dùng không tồn tại!', 'error');
            return redirect()->route('admin.user.index');
        }

        // Prevent deleting admin user
        if ($user->level == 1) {
            toast('Không thể xóa tài khoản quản trị viên!', 'error');
            return redirect()->route('admin.user.index');
        }

        // Prevent self-deletion
        if ($user->id == auth()->id()) {
            toast('Không thể xóa tài khoản của chính mình!', 'error');
            return redirect()->route('admin.user.index');
        }

        try {
            $rawAvatar = method_exists($user, 'getRawOriginal') ? $user->getRawOriginal('avatar') : $user->avatar;
            if ($rawAvatar) {
                $this->imageService->deleteImage($rawAvatar, 'users');
            }
            $this->userRepository->delete($uuid);
            toast('Xóa người dùng thành công!', 'success');
        } catch (\Exception $e) {
            toast('Có lỗi xảy ra khi xóa người dùng!', 'error');
        }

        return redirect()->route('admin.user.index');
    }

    public function bulkDelete(Request $request)
    {
        $uuids = $request->input('uuids', []);

        if (empty($uuids)) {
            toast('Vui lòng chọn người dùng cần xóa!', 'error');
            return redirect()->route('admin.user.index');
        }

        // Filter out admin users and current user
        $users = $this->model->whereIn('uuid', $uuids)->get();
        $deletableUuids = [];

        foreach ($users as $user) {
            if ($user->level != 1 && $user->id != auth()->id()) {
                $rawAvatar = method_exists($user, 'getRawOriginal') ? $user->getRawOriginal('avatar') : $user->avatar;
                if ($rawAvatar) {
                    $this->imageService->deleteImage($rawAvatar, 'users');
                }
                $deletableUuids[] = $user->uuid;
            }
        }

        if (empty($deletableUuids)) {
            toast('Không có người dùng nào có thể xóa!', 'error');
            return redirect()->route('admin.user.index');
        }

        try {
            $this->userRepository->deleteByUuids($deletableUuids);
            toast("Đã xóa " . count($deletableUuids) . " người dùng thành công!", 'success');
        } catch (\Exception $e) {
            toast('Có lỗi xảy ra khi xóa người dùng!', 'error');
        }

        return redirect()->route('admin.user.index');
    }
}