<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'string', 'regex:/^(?:\+84|0)\d{9}$/', 'unique:users,phone'],
            'password' => 'required|string|min:6|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'fullname.required' => 'Vui lòng nhập họ tên',
            'username.required' => 'Vui lòng nhập tên đăng nhập',
            'username.unique' => 'Tên đăng nhập đã tồn tại',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không đúng định dạng (ví dụ 0xxxxxxxxx hoặc +84xxxxxxxxx)',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('phone')) {
            $phone = $this->input('phone');
            // keep + sign and digits only
            $p = preg_replace('/[^+0-9]/', '', $phone);

            // remove multiple leading pluses if any
            $p = preg_replace('/^\++/', '+', $p);

            // If starts with +84 and then 9 digits -> keep
            if (preg_match('/^\+84\d{9}$/', $p)) {
                $normalized = $p;
            } else {
                // remove non-digits and handle leading 0 or 84
                $digits = preg_replace('/\D/', '', $p);

                if (preg_match('/^0\d{9}$/', $digits)) {
                    // 0xxxxxxxxx -> +84xxxxxxxxx (drop leading 0)
                    $normalized = '+84' . substr($digits, 1);
                } elseif (preg_match('/^84\d{9}$/', $digits)) {
                    // 84xxxxxxxxx -> +84xxxxxxxxx
                    $normalized = '+'.$digits;
                } else {
                    // fallback: keep digits as-is (validation will fail if wrong)
                    $normalized = $digits;
                }
            }

            $this->merge(['phone' => $normalized]);
        }
    }
}
