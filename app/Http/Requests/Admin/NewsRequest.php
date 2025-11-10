<?php

namespace App\Http\Requests\Admin;

class NewsRequest extends BaseAdminRequest
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
            'name_vn' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'intro_vn' => 'required|string|max:255',
            'intro_en' => 'nullable|string|max:255',
            'content_vn' => 'required|string',
            'content_en' => 'nullable|string',
            'category_id' => 'required|exists:tp_cate_news,id_cate_new',
            'slug' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
            'stt' => 'required|integer|min:0',
            'image' => request()->route('uuid')
            ? 'nullable|:tp_news,image,'.request()->route('uuid').',uuid|image|mimes:jpeg,png,jpg,gif,webp'
            : 'required|:tp_news,image|image|mimes:jpeg,png,jpg,gif,webp',
            'keywords' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name_vn.required' => 'Vui lòng nhập chủ đề tin tức',
            'name_vn.max' => 'Tên chủ đề không được quá 255 ký tự',
            'name_en.max' => 'Tên chủ đề không được quá 255 ký tự',
            'intro_vn.required' => 'Vui lòng nhập giới thiệu tin tức',
            'intro_vn.max' => 'Giới thiệu không được quá 255 ký tự',
            'intro_en.max' => 'Giới thiệu không được quá 255 ký tự',
            'content_vn.required' => 'Vui lòng nhập nội dung tin tức',
            'content_vn.max' => 'Nội dung không được quá 5,000,000 ký tự',
            'content_en.max' => 'Nội dung không được quá 5,000,000 ký tự',
            'category_id.required' => 'Vui lòng chọn chủ đề tin tức',
            'status.required' => 'Vui lòng chọn trạng thái tin tức',
            'stt.required' => 'Vui lòng nhập số thứ tự tin tức',
            'stt.integer' => 'Số thứ tự phải là số nguyên',
            'slug.max' => 'Slug không được quá 255 ký tự',
            'image.required' => 'Vui lòng chọn hình ảnh tin tức',
            'image.image' => 'Hình ảnh không hợp lệ',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif, webp',
            'image.max' => 'Hình ảnh không được quá 2MB',
            'category_id.exists' => 'Chủ đề tin tức không tồn tại',
            'keywords.required' => 'Vui lòng nhập từ khóa tin tức',
            'keywords.max' => 'Từ khóa không được quá 255 ký tự',
            'description.required' => 'Vui lòng nhập mô tả tin tức',
            'description.max' => 'Mô tả không được quá 255 ký tự',
        ];
    }
}
