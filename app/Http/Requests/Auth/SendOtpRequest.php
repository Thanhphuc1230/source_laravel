<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => ['required', 'string', 'regex:/^(?:\+84|0)\d{9}$/'],
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không đúng định dạng (ví dụ 0xxxxxxxxx hoặc +84xxxxxxxxx)',
        ];
    }
}
