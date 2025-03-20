<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CateProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name_vn' => [
                'required','max:255',
            ],
            'keywords' => 'required',
            'description' => 'required',
            'avatar' => [
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
            'avatar.image' => 'File phải là hình ảnh',
            'avatar.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif hoặc webp',
            'avatar.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
        ];
    }
}
