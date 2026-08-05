<?php

namespace App\Http\Requests\Admin;

class FeatureRequest extends BaseAdminRequest
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
            ? 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:51200'
            : 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:51200';

        return [
            'title_vn' => 'required|max:255',
            'title_en' => 'nullable|max:255',
            'content_vn' => 'required',
            'content_en' => 'nullable',
            'stt' => 'required|integer',
            'image' => $imageRule,
        ];
    }

    public function messages(): array
    {
        return [
            'title_vn.required' => 'Tiêu đề Tiếng Việt không được để trống',
            'content_vn.required' => 'Nội dung Tiếng Việt không được để trống',
            'stt.required' => 'Số thứ tự không được để trống',
            'stt.integer' => 'Số thứ tự phải là số',
            'image.required' => 'Hình ảnh không được để trống',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp, svg',
            'image.max' => 'Hình ảnh phải nhỏ hơn 2MB',
        ];
    }
}