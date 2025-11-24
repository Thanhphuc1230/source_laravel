<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MailTemplateRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:order,contact',
            'is_active' => 'boolean',
        ];

        if ($this->input('variables')) {
            $rules['variables'] = 'array';
            $rules['variables.*'] = 'string';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên template là bắt buộc',
            'name.max' => 'Tên template không được vượt quá 255 ký tự',
            'subject.required' => 'Tiêu đề là bắt buộc',
            'subject.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'content.required' => 'Nội dung template là bắt buộc',
            'type.required' => 'Loại template là bắt buộc',
            'type.in' => 'Loại template phải là order hoặc contact',
        ];
    }
}
