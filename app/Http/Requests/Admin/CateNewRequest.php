<?php

namespace App\Http\Requests\Admin;

class CateNewRequest extends BaseAdminRequest
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
            'name_vn' => ['required', 'max:255'],
            'name_en' => ['nullable', 'max:255'],
            'slug_vn' => ['nullable', 'max:255'],
            'slug_en' => ['nullable', 'max:255'],
            'keyword_vn' => 'required|max:255',
            'keyword_en' => 'nullable|max:255',
            'description_vn' => 'required',
            'description_en' => 'nullable',
            'image_vn' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'image_en' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name_vn.required' => 'Vui lòng nhập chủ đề tin tức VN',
            'name_vn.max' => 'Tên chủ đề VN không được quá 255 ký tự',
            'keyword_vn.required' => 'Vui lòng nhập từ khóa VN',
            'description_vn.required' => 'Vui lòng nhập mô tả ngắn VN',
            'image_vn.image' => 'File hình ảnh VN phải là hình ảnh',
            'image_vn.max' => 'File hình ảnh VN không được vượt quá 10MB',
            'image_en.image' => 'File hình ảnh EN phải là hình ảnh',
            'image_en.max' => 'File hình ảnh EN không được vượt quá 10MB',
        ];
    }
}
