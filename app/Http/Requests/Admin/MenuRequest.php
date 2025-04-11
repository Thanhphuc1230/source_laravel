<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
            'type' => 'required|in:page,cate_new,cate_product,link',
            'parent_id' => 'required|integer',
            'object_ids' => 'required_unless:type,link|array',
            'name_vn' => 'required_if:type,link',
            'link' => 'required_if:type,link|url',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Loại menu là bắt buộc.',
            'type.in' => 'Loại menu không hợp lệ.',
            'parent_id.required' => 'Vị trí cha là bắt buộc.',
            'parent_id.integer' => 'Vị trí cha phải là số.',
            'object_ids.required_unless' => 'Chủ đề là bắt buộc khi không phải kiểu link.',
            'object_ids.array' => 'Chủ đề phải là mảng.',
            'name_vn.required_if' => 'Tên menu là bắt buộc khi chọn kiểu link.',
            'link.required_if' => 'Đường dẫn là bắt buộc khi chọn kiểu link.',
            'link.url' => 'Đường dẫn không hợp lệ.',
        ];
    }
}
