<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * Only authenticated admin users (level >= 1) can access admin functions
     */
    public function authorize(): bool
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return false;
        }

        // Check if user has admin level (level >= 1)
        $user = auth()->user();
        
        return $user->level >= 1 && $user->status == 1;
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute là bắt buộc.',
            'string' => ':attribute phải là chuỗi.',
            'max' => ':attribute không được vượt quá :max ký tự.',
            'min' => ':attribute phải có ít nhất :min ký tự.',
            'numeric' => ':attribute phải là số.',
            'integer' => ':attribute phải là số nguyên.',
            'boolean' => ':attribute phải là true hoặc false.',
            'exists' => ':attribute không tồn tại.',
            'unique' => ':attribute đã tồn tại.',
            'image' => ':attribute phải là ảnh.',
            'mimes' => ':attribute phải có định dạng: :values.',
            'dimensions' => ':attribute không đúng kích thước yêu cầu.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'name_vn' => 'Tên tiếng Việt',
            'name_en' => 'Tên tiếng Anh',
            'content_vn' => 'Nội dung tiếng Việt',
            'content_en' => 'Nội dung tiếng Anh',
            'image' => 'Hình ảnh',
            'status' => 'Trạng thái',
            'stt' => 'Thứ tự',
            'category_id' => 'Danh mục',
            'slug' => 'Đường dẫn',
            'keywords' => 'Từ khóa',
            'description' => 'Mô tả',
        ];
    }
}
