<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => ['required', 'string', 'regex:/^(?:\+84|0)\d{9}$/'],
            'otp' => ['required', 'digits:6'],
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không đúng định dạng',
            'otp.required' => 'Vui lòng nhập mã OTP',
            'otp.digits' => 'Mã OTP phải gồm 6 chữ số',
        ];
    }
}
