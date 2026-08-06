<?php

namespace App\Http\Requests\Admin;

class ProfileRequest extends BaseAdminRequest
{
    // Authorization is handled by BaseAdminRequest

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$userId,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'username' => 'required|string|max:255|unique:users,username,'.$userId,
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'Tên không được để trống',
            'fullname.string' => 'Tên không được chứa ký tự đặc biệt',
            'fullname.max' => 'Tên không được vượt quá 255 ký tự',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'avatar.image' => 'File ảnh không đúng định dạng',
            'avatar.mimes' => 'File ảnh không đúng định dạng',
            'avatar.max' => 'File ảnh không được vượt quá 10MB',
            'username.required' => 'Tên tài khoản không được để trống',
            'username.string' => 'Tên tài khoản không được chứa ký tự đặc biệt',
            'username.max' => 'Tên tài khoản không được vượt quá 255 ký tự',
            'username.unique' => 'Tên tài khoản đã tồn tại',
        ];
    }
}
