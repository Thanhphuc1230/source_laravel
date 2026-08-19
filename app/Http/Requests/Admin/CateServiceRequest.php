<?php

namespace App\Http\Requests\Admin;

class CateServiceRequest extends BaseAdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name_vn' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'slug_vn' => 'nullable|string|max:255',
            'slug_en' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
            'stt' => 'required|integer|min:0',
            'parent_id' => 'required|integer|min:0',
            'image_vn' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'image_en' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'keyword_vn' => 'nullable|string|max:255',
            'keyword_en' => 'nullable|string|max:255',
            'description_vn' => 'nullable|string|max:255',
            'description_en' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name_vn.required' => 'Vui lòng nhập tên danh mục dịch vụ',
            'name_vn.max' => 'Tên danh mục không được quá 255 ký tự',
            'status.required' => 'Vui lòng chọn trạng thái',
            'stt.required' => 'Vui lòng nhập số thứ tự',
            'stt.integer' => 'Số thứ tự phải là số nguyên',
        ];
    }
}
