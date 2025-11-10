<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $uuid = $this->route('uuid') ?? $this->route('user');

        $rules = [
            'fullname' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username' . ($uuid ? ',' . $uuid . ',uuid' : '')
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email' . ($uuid ? ',' . $uuid . ',uuid' : '')
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(?:\+84|0)\d{9}$/',
                'unique:users,phone' . ($uuid ? ',' . $uuid . ',uuid' : '')
            ],
            'level' => 'required|integer|in:1,2,3',
            'status' => 'required|in:0,1',
            'roles' => 'array',
            'roles.*' => 'exists:tp_roles,id'
        ];

        // Password is required for create, optional for update
        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:6|confirmed';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'fullname.required' => 'Vui lòng nhập họ tên',
            'fullname.max' => 'Họ tên không được vượt quá 255 ký tự',
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.unique' => 'Tên đăng nhập đã tồn tại',
            'username.max' => 'Tên đăng nhập không được vượt quá 255 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'phone.regex' => 'Số điện thoại không đúng định dạng (ví dụ: 0xxxxxxxxx hoặc +84xxxxxxxxx)',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'level.required' => 'Vui lòng chọn cấp độ',
            'level.in' => 'Cấp độ không hợp lệ',
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.boolean' => 'Trạng thái không hợp lệ',
            'roles.array' => 'Vai trò phải là mảng',
            'roles.*.exists' => 'Vai trò không tồn tại'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Normalize phone number
        if ($this->has('phone') && !empty($this->phone)) {
            $phone = $this->phone;
            $normalized = preg_replace('/[^+0-9]/', '', $phone);
            $this->merge(['phone' => $normalized]);
        }

        // Convert status to integer
        if ($this->has('status')) {
            $this->merge(['status' => (int) $this->status]);
        }
    }
}