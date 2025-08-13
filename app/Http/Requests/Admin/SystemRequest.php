<?php

namespace App\Http\Requests\Admin;

class SystemRequest extends BaseAdminRequest
{
    /**
     * Only Super Admin (level = 1) can access system settings
     */
    public function authorize(): bool
    {
        if (!parent::authorize()) {
            return false;
        }

        // System settings require highest privilege
        return auth()->user()->level == 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
