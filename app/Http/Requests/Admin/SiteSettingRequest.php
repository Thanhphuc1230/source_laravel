<?php

namespace App\Http\Requests\Admin;

class SiteSettingRequest extends BaseAdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'key' => 'required|string|max:255|unique:site_settings,key,' . ($this->route('site_setting') ?? ''),
            'type' => 'required|string|in:text,json,image,editor',
            'value' => 'nullable|string',
            'group' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'key.required' => 'Key là bắt buộc.',
            'key.unique' => 'Key đã tồn tại.',
            'type.required' => 'Type là bắt buộc.',
            'type.in' => 'Type không hợp lệ.',
        ];
    }
}