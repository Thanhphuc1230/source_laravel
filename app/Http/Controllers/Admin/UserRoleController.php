<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserRoleController extends Controller
{
    protected $nameItem = 'phân quyền người dùng';
    protected $nameClass = 'user-role';

    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fullname', 'LIKE', "%{$search}%")
                  ->orWhere('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $list = $query->with('roles')->orderBy('id', 'desc')->paginate(15);
        
        return view('admin.modules.user-role.list', compact('list'))
            ->with('nameItem', $this->nameItem)
            ->with('nameClass', $this->nameClass);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::with('permissions')->get();
        $permissions = Permission::orderBy('group_name')->orderBy('display_name')->get();
        $permissionGroups = $permissions->groupBy('group_name');
        $userRoles = $user->roles->pluck('id')->toArray();
        
        // Get user's direct permissions (not through roles)
        $userDirectPermissions = $user->directPermissions->pluck('id')->toArray();

        return view('admin.modules.user-role.edit', compact(
            'user', 'roles', 'permissions', 'permissionGroups', 
            'userRoles', 'userDirectPermissions'
        ))
            ->with('nameItem', $this->nameItem)
            ->with('nameClass', $this->nameClass);
    }

    public function updateRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $roles = $request->input('roles', []);
        $permissions = $request->input('permissions', []);
        $assignmentType = $request->input('assignment_type', 'roles');

        if ($assignmentType === 'roles') {
            // Sync roles and clear direct permissions
            $user->syncRoles($roles);
            $user->syncDirectPermissions([]);
        } else {
            // Clear roles and sync direct permissions
            $user->syncRoles([]);
            $user->syncDirectPermissions($permissions);
        }

        // Clear cache to refresh permissions
        $user->forgetCachedPermissions();

        toast('Cập nhật phân quyền thành công!', 'success');
        return redirect()->route('admin.user-role.index');
    }

    public function bulkUpdateRoles(Request $request)
    {
        $userIds = $request->input('user_ids', []);
        $roles = $request->input('roles', []);

        if (empty($userIds)) {
            toast('Vui lòng chọn người dùng!', 'error');
            return redirect()->back();
        }

        if (empty($roles)) {
            toast('Vui lòng chọn vai trò!', 'error');
            return redirect()->back();
        }

        $successCount = 0;
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $user->syncRoles($roles);
                $successCount++;
            }
        }

        if ($successCount > 0) {
            toast("Cập nhật phân quyền cho " . $successCount . " người dùng thành công!", 'success');
        } else {
            toast('Không có người dùng nào được cập nhật!', 'warning');
        }
        return redirect()->route('admin.user-role.index');
    }

    // Role Management Methods
    public function roleIndex()
    {
        $roles = Role::with('permissions')->orderBy('id')->get();
        return view('admin.modules.user-role.roles.index', compact('roles'))
            ->with('nameItem', 'quản lý vai trò')
            ->with('nameClass', 'role-management');
    }

    public function roleCreate()
    {
        $permissions = Permission::orderBy('group_name')->orderBy('display_name')->get();
        $permissionGroups = $permissions->groupBy('group_name');
        
        return view('admin.modules.user-role.roles.create', compact('permissions', 'permissionGroups'))
            ->with('nameItem', 'tạo vai trò mới')
            ->with('nameClass', 'role-create');
    }

    public function roleStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tp_roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array'
        ]);

        $role = Role::create($request->only(['name', 'display_name', 'description']));
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        toast('Tạo vai trò thành công!', 'success');
        return redirect()->route('admin.user-role.roles.index');
    }

    public function roleEdit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('group_name')->orderBy('display_name')->get();
        $permissionGroups = $permissions->groupBy('group_name');
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('admin.modules.user-role.roles.edit', compact('role', 'permissions', 'permissionGroups', 'rolePermissions'))
            ->with('nameItem', 'chỉnh sửa vai trò')
            ->with('nameClass', 'role-edit');
    }

    public function roleUpdate(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:tp_roles,name,' . $id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array'
        ]);

        $role->update($request->only(['name', 'display_name', 'description']));
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        toast('Cập nhật vai trò thành công!', 'success');
        return redirect()->route('admin.user-role.roles.index');
    }

    public function roleDestroy($id)
    {
        $role = Role::findOrFail($id);
        
        // Check if role is being used by users
        if ($role->users()->count() > 0) {
            toast('Không thể xóa vai trò này vì đang được sử dụng!', 'error');
            return redirect()->back();
        }

        $role->delete();
        toast('Xóa vai trò thành công!', 'success');
        return redirect()->route('admin.user-role.roles.index');
    }
}
