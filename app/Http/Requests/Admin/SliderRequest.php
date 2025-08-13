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
        return [
            'name_vn' => 'required|max:255',
            'stt' => 'required|integer',
            'image' => request()->route('uuid') ? 'nullable|unique:tp_sliders,image,' . request()->route('uuid') . ',uuid' : 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name_vn.required' => 'Tên slider không được để trống',
            'stt.required' => 'Số thứ tự không được để trống',
            'stt.integer' => 'Số thứ tự phải là số',
            'image.required' => 'Ảnh không được để trống',
            'image.image' => 'Ảnh phải là hình ảnh',
            'image.mimes' => 'Ảnh phải là hình ảnh',
            'image.max' => 'Ảnh phải nhỏ hơn 2MB',
        ];
    }
}
