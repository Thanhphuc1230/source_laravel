<?php

namespace App\Http\Requests\Admin;

class SystemRequest extends BaseAdminRequest
{
    /**
     * Only Super Admin (level = 1) can access system settings
     * Authorization is handled by BaseAdminRequest (level >= 1)
     * Admin and Staff can access system settings
     */
    public function authorize(): bool
    {
        // Use BaseAdminRequest authorization (level >= 1)
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Thông tin liên hệ cơ bản
            'email' => 'required|email|max:50',
            'email_alert' => 'nullable|email|max:50',
            'address' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',

            // Footer content
            'footer_vn' => 'nullable|string|max:2000',
            'footer_en' => 'nullable|string|max:2000',

            // Social Media Links
            'facebook' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'zalo' => 'nullable|string|max:255',

            // SEO & Branding
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:512',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'watermark' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            'name_vn' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'keyword' => 'nullable|string|max:1000',

            // Map embed
            'map' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            // Email validation
            'email.required' => 'Email hệ thống là bắt buộc',
            'email.email' => 'Email hệ thống không đúng định dạng',
            'email.max' => 'Email hệ thống không được quá 50 ký tự',
            'email_alert.email' => 'Email cảnh báo không đúng định dạng',
            'email_alert.max' => 'Email cảnh báo không được quá 50 ký tự',

            // Contact info
            'address.max' => 'Địa chỉ không được quá 1000 ký tự',
            'phone.max' => 'Số điện thoại không được quá 20 ký tự',

            // Footer
            'footer_vn.max' => 'Footer tiếng Việt không được quá 2000 ký tự',
            'footer_en.max' => 'Footer tiếng Anh không được quá 2000 ký tự',

            // Social Media
            'facebook.url' => 'Link Facebook không đúng định dạng',
            'youtube.url' => 'Link YouTube không đúng định dạng',
            'twitter.url' => 'Link Twitter không đúng định dạng',
            'instagram.url' => 'Link Instagram không đúng định dạng',
            'zalo.max' => 'Link Zalo không được quá 255 ký tự',

            // Files
            'favicon.image' => 'Favicon phải là file hình ảnh',
            'favicon.mimes' => 'Favicon phải có định dạng: ico, png, jpg, jpeg',
            'favicon.max' => 'Favicon không được quá 512KB',
            'logo.image' => 'Logo phải là file hình ảnh',
            'logo.mimes' => 'Logo phải có định dạng: png, jpg, jpeg, svg',
            'logo.max' => 'Logo không được quá 2MB',
            'watermark.image' => 'Watermark phải là file hình ảnh',
            'watermark.mimes' => 'Watermark phải có định dạng: png, jpg, jpeg',
            'watermark.max' => 'Watermark không được quá 1MB',

            // SEO
            'name_vn.max' => 'Tên website không được quá 255 ký tự',
            'description.max' => 'Mô tả không được quá 500 ký tự',
            'keyword.max' => 'Từ khóa không được quá 1000 ký tự',

            // Map
            'map.max' => 'Mã nhúng bản đồ không được quá 2000 ký tự',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'email' => 'Email hệ thống',
            'email_alert' => 'Email cảnh báo',
            'address' => 'Địa chỉ',
            'phone' => 'Số điện thoại',
            'footer_vn' => 'Footer tiếng Việt',
            'footer_en' => 'Footer tiếng Anh',
            'facebook' => 'Facebook',
            'youtube' => 'YouTube',
            'twitter' => 'Twitter',
            'instagram' => 'Instagram',
            'zalo' => 'Zalo',
            'favicon' => 'Favicon',
            'logo' => 'Logo',
            'watermark' => 'Watermark',
            'name_vn' => 'Tên website',
            'description' => 'Mô tả',
            'keyword' => 'Từ khóa',
            'map' => 'Bản đồ',
        ];
    }
}
