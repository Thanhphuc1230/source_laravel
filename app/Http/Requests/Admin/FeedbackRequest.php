<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FeedBackRequest extends FormRequest
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
            'name' => 'required|max:255',
            'message' => 'required|max:65535',
            'image' => request()->route('uuid')
            ? 'nullable|:tp_products,image,' . request()->route('uuid') . ',uuid|image|mimes:jpeg,png,jpg,gif,webp'
            : 'required|:tp_products,image|image|mimes:jpeg,png,jpg,gif,webp',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống',
            'name.max' => 'Tên không được quá 255 ký tự',
            'message.required' => 'Nội dung không được để trống',
            'message.max' => 'Nội dung không được quá 65535 ký tự',
            'image.required' => 'Hình ảnh không được để trống',
            'image.image' => 'Hình ảnh không đúng định dạng',
            'image.mimes' => 'Hình ảnh không đúng định dạng',
            'image.max' => 'Hình ảnh không được vượt quá 2MB',
        ];
    }
}
