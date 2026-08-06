<?php

namespace App\Http\Requests\Admin;

class FeedBackRequest extends BaseAdminRequest
{
    // Authorization is handled by BaseAdminRequest

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s\p{L}]+$/u',
            'message' => 'required|string|max:2000', // Reduced from 65535 for security
            'image' => request()->route('uuid')
            ? 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
            : 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống',
            'name.max' => 'Tên không được quá 255 ký tự',
            'name.regex' => 'Tên chỉ được chứa chữ cái, số và khoảng trắng',
            'message.required' => 'Nội dung không được để trống',
            'message.max' => 'Nội dung không được quá 2000 ký tự',
            'image.required' => 'Hình ảnh không được để trống',
            'image.image' => 'Hình ảnh không đúng định dạng',
            'image.mimes' => 'Hình ảnh không đúng định dạng',
            'image.max' => 'Hình ảnh không được vượt quá 10MB',
        ];
    }
}
