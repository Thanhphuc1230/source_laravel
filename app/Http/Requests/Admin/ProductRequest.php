<?php

namespace App\Http\Requests\Admin;

class ProductRequest extends BaseAdminRequest
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

            'slug_vn' => 'nullable|string|max:255',
            'slug_en' => 'nullable|string|max:255',

            'keyword_vn' => 'required|max:255',
            'keyword_en' => 'nullable|max:255',
            'description_vn' => 'required|max:255',
            'description_en' => 'nullable|max:255',

            'image_vn' => request()->route('uuid')
            ? 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200'
            : 'required|image|mimes:jpeg,png,jpg,gif,webp|max:51200',
            'image_en' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200',

            'image_detail' => 'nullable|array',
            'image_detail.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:51200',

            'price' => 'required|numeric|min:0',
            'price_old' => 'nullable|numeric|min:0',

            'content_vn' => 'required|string',
            'content_en' => 'nullable|string',

            'category_id' => 'required|exists:tp_cate_products,id_cate_product',

            'status' => 'required|boolean',
            'hot' => 'nullable|boolean',
            'stt' => 'required|integer|min:0',
            'intro_vn' => 'required|max:1000',
            'intro_en' => 'nullable|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name_vn.required' => 'Tên sản phẩm tiếng Việt là bắt buộc.',
            'name_vn.string' => 'Tên sản phẩm tiếng Việt phải là chuỗi.',
            'name_vn.max' => 'Tên sản phẩm tiếng Việt không được vượt quá 255 ký tự.',
            'name_en.string' => 'Tên sản phẩm tiếng Anh phải là chuỗi.',
            'name_en.max' => 'Tên sản phẩm tiếng Anh không được vượt quá 255 ký tự.',
            'slug_vn.max' => 'Slug VN không được vượt quá 255 ký tự.',
            'slug_en.max' => 'Slug EN không được vượt quá 255 ký tự.',
            'keyword_vn.required' => 'Từ khóa tiếng Việt là bắt buộc.',
            'keyword_vn.max' => 'Từ khóa tiếng Việt không được vượt quá 255 ký tự.',
            'keyword_en.max' => 'Từ khóa tiếng Anh không được vượt quá 255 ký tự.',
            'description.required' => 'Mô tả là bắt buộc.',
            'description.max' => 'Mô tả không được vượt quá 255 ký tự.',
            'image.required' => 'Ảnh là bắt buộc.',
            'image.image' => 'Ảnh phải là file ảnh.',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif, svg.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
            'image_detail.array' => 'Ảnh chi tiết phải là mảng.',
            'image_detail.*.image' => 'Ảnh chi tiết phải là file ảnh.',
            'image_detail.*.mimes' => 'Ảnh chi tiết phải có định dạng jpeg, png, jpg, gif, svg.',
            'image_detail.*.max' => 'Ảnh chi tiết không được vượt quá 2MB.',

            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm không được nhỏ hơn 0.',
            'price_old.numeric' => 'Giá cũ phải là số.',
            'price_old.min' => 'Giá cũ không được nhỏ hơn 0.',
            'content_vn.required' => 'Nội dung sản phẩm tiếng Việt là bắt buộc.',
            'content_vn.string' => 'Nội dung sản phẩm tiếng Việt phải là chuỗi.',
            'content_en.string' => 'Nội dung sản phẩm tiếng Anh phải là chuỗi.',
            'category_id.required' => 'Danh mục sản phẩm là bắt buộc.',
            'category_id.exists' => 'Danh mục sản phẩm không tồn tại.',
            'status.required' => 'Trạng thái sản phẩm là bắt buộc.',
            'status.boolean' => 'Trạng thái sản phẩm phải là boolean.',
            'hot.boolean' => 'Trạng thái hot phải là boolean.',
            'stt.required' => 'Thứ tự sản phẩm là bắt buộc.',
            'stt.integer' => 'Thứ tự sản phẩm phải là số.',
            'stt.min' => 'Thứ tự sản phẩm không được nhỏ hơn 0.',
            'intro_vn.required' => 'Giới thiệu tiếng Việt là bắt buộc.',
            'intro_vn.max' => 'Giới thiệu tiếng Việt không được vượt quá 1000 ký tự.',
            'intro_en.max' => 'Giới thiệu tiếng Anh không được vượt quá 1000 ký tự.',
        ];
    }
}
