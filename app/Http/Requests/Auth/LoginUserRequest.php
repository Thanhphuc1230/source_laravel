<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Accept formats like 0xxxxxxxxx or +84xxxxxxxxx (9 digits after prefix)
            'phone' => ['required', 'string', 'regex:/^(?:\+84|0)\d{9}$/'],
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không đúng định dạng (ví dụ 0xxxxxxxxx hoặc +84xxxxxxxxx)',
            'password.required' => 'Vui lòng nhập mật khẩu của bạn',
        ];
    }

    /**
     * Normalize phone before validation (remove spaces, dashes)
     */
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
