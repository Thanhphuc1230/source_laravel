<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class ProductSettingRequest extends BaseAdminRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Authorization is handled by BaseAdminRequest (level >= 1)
     * Admin and Staff can access product settings
     */
    public function authorize(): bool
    {
        // Use BaseAdminRequest authorization (level >= 1)
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $settingId = $this->route('uuid') ?
            \App\Models\ProductSetting::where('uuid', $this->route('uuid'))->first()?->id :
            null;

        return [
            'key' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9_]+$/', // Only lowercase, numbers, underscores
                Rule::unique('product_settings', 'key')->ignore($settingId),
            ],
            'value' => [
                'required',
                function ($attribute, $value, $fail) {
                    $type = $this->input('type');

                    switch ($type) {
                        case 'number':
                            if (! is_numeric($value)) {
                                $fail('Value must be a valid number for number type.');
                            }
                            break;
                        case 'json':
                            if (! $this->isValidJson($value)) {
                                $fail('Value must be valid JSON for json type.');
                            }
                            break;
                        case 'boolean':
                            if (! in_array($value, ['true', 'false'])) {
                                $fail('Value must be true or false for boolean type.');
                            }
                            break;
                        case 'html':
                            // Allow HTML content, just check it's not empty
                            if (empty(trim(strip_tags($value)))) {
                                $fail('HTML value cannot be empty.');
                            }
                            break;
                    }
                },
            ],
            'type' => [
                'required',
                'string',
                Rule::in(['text', 'number', 'json', 'html', 'boolean']),
            ],
            'group' => [
                'required',
                'string',
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'key.required' => 'Setting key là bắt buộc.',
            'key.unique' => 'Setting key đã tồn tại.',
            'key.regex' => 'Setting key chỉ được chứa chữ thường, số và dấu gạch dưới.',
            'value.required' => 'Giá trị là bắt buộc.',
            'type.required' => 'Loại dữ liệu là bắt buộc.',
            'type.in' => 'Loại dữ liệu không hợp lệ.',
            'group.required' => 'Nhóm là bắt buộc.',
            'sort_order.integer' => 'Thứ tự sắp xếp phải là số nguyên.',
            'sort_order.min' => 'Thứ tự sắp xếp không được nhỏ hơn 0.',
            'sort_order.max' => 'Thứ tự sắp xếp không được lớn hơn 9999.',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'key' => 'Setting Key',
            'value' => 'Giá trị',
            'type' => 'Loại dữ liệu',
            'group' => 'Nhóm',
            'description' => 'Mô tả',
            'sort_order' => 'Thứ tự sắp xếp',
            'is_active' => 'Trạng thái kích hoạt',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert checkbox value to boolean
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->boolean('is_active'),
            ]);
        } else {
            $this->merge([
                'is_active' => false,
            ]);
        }

        // Clean and normalize key
        if ($this->has('key')) {
            $this->merge([
                'key' => strtolower(trim($this->input('key'))),
            ]);
        }
    }

    /**
     * Check if string is valid JSON.
     */
    private function isValidJson(string $string): bool
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }
}
