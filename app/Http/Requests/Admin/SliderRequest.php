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
            ? 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200'
            : 'required|image|mimes:jpeg,png,jpg,gif,webp|max:51200';

        return [
            'name_vn' => 'required|max:255',
            'name_en' => 'nullable|max:255',
            'stt' => 'required|integer',
            'image_desktop_vn' => $imageRule,
            'image_desktop_en' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200',
            'image_mobile_vn' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200',
            'image_mobile_en' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200',
        ];
    }

    public function messages(): array
    {
        return [
            'name_vn.required' => 'Tên slider Tiếng Việt không được để trống',
            'stt.required' => 'Số thứ tự không được để trống',
            'stt.integer' => 'Số thứ tự phải là số',
            'image_desktop_vn.required' => 'Ảnh Desktop Tiếng Việt không được để trống',
            'image_desktop_vn.image' => 'Ảnh Desktop Tiếng Việt phải là hình ảnh',
            'image_desktop_vn.max' => 'Ảnh Desktop Tiếng Việt phải nhỏ hơn 2MB',
            'image_desktop_en.image' => 'Ảnh Desktop Tiếng Anh phải là hình ảnh',
            'image_desktop_en.max' => 'Ảnh Desktop Tiếng Anh phải nhỏ hơn 2MB',
            'image_mobile_vn.image' => 'Ảnh Mobile Tiếng Việt phải là hình ảnh',
            'image_mobile_vn.max' => 'Ảnh Mobile Tiếng Việt phải nhỏ hơn 2MB',
            'image_mobile_en.image' => 'Ảnh Mobile Tiếng Anh phải là hình ảnh',
            'image_mobile_en.max' => 'Ảnh Mobile Tiếng Anh phải nhỏ hơn 2MB',
        ];
    }
}
