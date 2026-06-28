<?php

namespace App\Http\Requests\Admin;

class PageRequest extends BaseAdminRequest
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
            'name_vn' => [
                'required', 'max:255',
            ],
            'name_en' => [
                'nullable', 'max:255',
            ],
            'content_vn' => 'required|max:65535',
            'content_en' => 'nullable|max:65535',
            'slug_vn' => 'nullable|max:255',
            'slug_en' => 'nullable|max:255',
            'keyword_vn' => 'required|max:255',
            'keyword_en' => 'nullable|max:255',
            'description_vn' => 'required',
            'description_en' => 'nullable',
            'image_vn' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_en' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
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
            'name_vn.required' => 'Vui lòng nhập chủ đề sản phẩm',
            'name_vn.unique' => 'Chủ đề sản phẩm này đã tồn tại',
            'name_vn.max' => 'Tên chủ đề không được quá 255 ký tự',
            'slug.required' => 'Vui lòng nhập slug sản phẩm',
            'slug.unique' => 'Slug sản phẩm này đã tồn tại',
            'keywords.required' => 'Vui lòng nhập từ khóa',
            'description.required' => 'Vui lòng nhập mô tả ngắn',
            'content_vn.required' => 'Vui lòng nhập nội dung trang',
            'content_vn.max' => 'Nội dung trang không được quá 65535 ký tự',
            'content_en.max' => 'Nội dung trang tiếng Anh không được quá 65535 ký tự',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif hoặc webp',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
        ];
    }
}
