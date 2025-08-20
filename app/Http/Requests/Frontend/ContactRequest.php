<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
        return [
            'fullname' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\p{L}]+$/u',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20|regex:/^[\d\s\+\-\(\)]+$/',
            'subject' => 'required|string|max:500',
            'message' => 'required|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'Tên không được để trống',
            'fullname.max' => 'Tên không được vượt quá 255 ký tự',
            'fullname.regex' => 'Tên chỉ được chứa chữ cái, số và khoảng trắng',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.max' => 'Email không được vượt quá 255 ký tự',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'phone.regex' => 'Số điện thoại chỉ được chứa số, dấu +, -, (), và khoảng trắng',
            'subject.required' => 'Tiêu đề không được để trống',
            'subject.max' => 'Tiêu đề không được vượt quá 500 ký tự',
            'message.required' => 'Nội dung không được để trống',
            'message.max' => 'Nội dung không được vượt quá 2000 ký tự',
        ];
    }
}
