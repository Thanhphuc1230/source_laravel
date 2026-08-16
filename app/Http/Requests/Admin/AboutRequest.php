<?php

namespace App\Http\Requests\Admin;

class AboutRequest extends BaseAdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_vn' => 'required|max:255',
            'name_en' => 'nullable|max:255',
            'content_vn' => 'required',
            'content_en' => 'nullable',
            'intro_vn' => 'nullable',
            'intro_en' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'link' => 'nullable|string|max:255',
            'status' => 'nullable|integer',
            'stt' => 'nullable|integer',

            // Stats validation array
            'stats' => 'nullable|array',
            'stats.*.value' => 'required|string|max:50',
            'stats.*.name_vn' => 'required|string|max:255',
            'stats.*.name_en' => 'nullable|string|max:255',

            // Stats files validation (for uploaded icons)
            'stats_files' => 'nullable|array',
            'stats_files.*.icon' => 'nullable|image|mimes:png,svg,jpg,jpeg,gif,webp|max:2048',
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
            'name_vn.required' => 'Vui lòng nhập tiêu đề giới thiệu',
            'name_vn.max' => 'Tiêu đề không được quá 255 ký tự',
            'content_vn.required' => 'Vui lòng nhập nội dung chi tiết',
            'image.image' => 'File tải lên phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif hoặc webp',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 10MB',
            'stats.*.value.required' => 'Vui lòng nhập giá trị chỉ số',
            'stats.*.name_vn.required' => 'Vui lòng nhập tên Tiếng Việt cho chỉ số',
        ];
    }
}
