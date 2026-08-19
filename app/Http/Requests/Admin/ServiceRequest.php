<?php

namespace App\Http\Requests\Admin;

class ServiceRequest extends BaseAdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name_vn' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'intro_vn' => 'required|string|max:255',
            'intro_en' => 'nullable|string|max:255',
            'content_vn' => 'required|string',
            'content_en' => 'nullable|string',
            'category_id' => 'required|exists:tp_cate_services,id_cate_service',
            'slug_vn' => 'nullable|string|max:255',
            'slug_en' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
            'stt' => 'required|integer|min:0',
            'image_vn' => request()->route('uuid')
                ? 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
                : 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'image_en' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'keyword_vn' => 'required|string|max:255',
            'keyword_en' => 'nullable|string|max:255',
            'description_vn' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name_vn.required' => 'Vui lòng nhập tên dịch vụ',
            'name_vn.max' => 'Tên dịch vụ không được quá 255 ký tự',
            'intro_vn.required' => 'Vui lòng nhập mô tả ngắn dịch vụ',
            'content_vn.required' => 'Vui lòng nhập nội dung dịch vụ',
            'category_id.required' => 'Vui lòng chọn danh mục dịch vụ',
            'category_id.exists' => 'Danh mục dịch vụ không tồn tại',
            'status.required' => 'Vui lòng chọn trạng thái',
            'stt.required' => 'Vui lòng nhập số thứ tự',
            'stt.integer' => 'Số thứ tự phải là số nguyên',
            'image_vn.required' => 'Vui lòng chọn hình ảnh dịch vụ',
            'keyword_vn.required' => 'Vui lòng nhập từ khóa tiếng Việt',
            'description_vn.required' => 'Vui lòng nhập mô tả tiếng Việt',
        ];
    }
}
