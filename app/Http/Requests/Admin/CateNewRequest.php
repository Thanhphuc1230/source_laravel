<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CateNewRequest extends FormRequest
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
            'name_vn' => [
                'required','max:255',
            ],
            'keywords' => 'required',
            'description' => 'required',
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // Giới hạn kích thước file 2MB
            ],
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
            'name_vn.required' => 'Vui lòng nhập chủ đề sản phẩm',
            'name_vn.unique' => 'Chủ đề sản phẩm này đã tồn tại',
            'name_vn.max' => 'Tên chủ đề không được quá 255 ký tự',
            'slug.required' => 'Vui lòng nhập slug sản phẩm',
            'slug.unique' => 'Slug sản phẩm này đã tồn tại',
            'keywords.required' => 'Vui lòng nhập từ khóa',
            'description.required' => 'Vui lòng nhập mô tả ngắn',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif hoặc webp',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
        ];
    }
}
