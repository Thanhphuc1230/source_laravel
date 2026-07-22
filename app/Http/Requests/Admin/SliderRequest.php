<?php

namespace App\Http\Requests\Admin;

class SliderRequest extends BaseAdminRequest
{
    // Authorization is handled by BaseAdminRequest

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $imageRule = request()->route('uuid')
            ? 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            : 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';

        return [
            'name_vn' => 'required|max:255',
            'name_en' => 'nullable|max:255',
            'stt' => 'required|integer',
            'image_vn' => $imageRule,
            'image_en' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name_vn.required' => 'Tên slider Tiếng Việt không được để trống',
            'stt.required' => 'Số thứ tự không được để trống',
            'stt.integer' => 'Số thứ tự phải là số',
            'image_vn.required' => 'Ảnh Tiếng Việt không được để trống',
            'image_vn.image' => 'Ảnh Tiếng Việt phải là hình ảnh',
            'image_vn.max' => 'Ảnh Tiếng Việt phải nhỏ hơn 2MB',
            'image_en.image' => 'Ảnh Tiếng Anh phải là hình ảnh',
            'image_en.max' => 'Ảnh Tiếng Anh phải nhỏ hơn 2MB',
        ];
    }
}
