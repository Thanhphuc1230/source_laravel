<?php

namespace App\Http\Requests\Admin;

class MailConfigRequest extends BaseAdminRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT');

        $rules = [
            'mailer' => 'required|string|in:smtp,mailgun,ses,sendmail',
            'host' => 'required_if:mailer,smtp|string|max:255',
            'port' => 'required_if:mailer,smtp|integer|min:1|max:65535',
            'username' => 'required_if:mailer,smtp,mailgun|string|max:255',
            'password' => ($isUpdate ? 'nullable|' : 'required_if:mailer,smtp,mailgun|') . 'string|max:255',
            'encryption' => 'nullable|string|in:tls,ssl',
            'from_address' => 'required|email|max:255',
            'from_name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];

        // Additional validation for different mailers
        if ($this->input('mailer') === 'mailgun') {
            $rules['host'] = 'nullable|string|max:255';
            $rules['port'] = 'nullable|integer|min:1|max:65535';
        }

        if ($this->input('mailer') === 'ses') {
            $rules['host'] = 'nullable|string|max:255';
            $rules['port'] = 'nullable|integer|min:1|max:65535';
        }

        if ($this->input('mailer') === 'sendmail') {
            $rules['host'] = 'nullable|string|max:255';
            $rules['port'] = 'nullable|integer|min:1|max:65535';
            $rules['username'] = 'nullable|string|max:255';
            $rules['password'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'mailer.required' => 'Loại mailer là bắt buộc',
            'mailer.in' => 'Loại mailer không hợp lệ',
            'host.required_if' => 'Host là bắt buộc cho SMTP',
            'host.max' => 'Host không được quá 255 ký tự',
            'port.required_if' => 'Port là bắt buộc cho SMTP',
            'port.integer' => 'Port phải là số nguyên',
            'port.min' => 'Port phải lớn hơn 0',
            'port.max' => 'Port không được quá 65535',
            'username.required_if' => 'Username là bắt buộc',
            'username.max' => 'Username không được quá 255 ký tự',
            'password.required_if' => 'Password là bắt buộc',
            'password.max' => 'Password không được quá 255 ký tự',
            'encryption.in' => 'Encryption phải là tls hoặc ssl',
            'from_address.required' => 'Email gửi là bắt buộc',
            'from_address.email' => 'Email gửi không đúng định dạng',
            'from_address.max' => 'Email gửi không được quá 255 ký tự',
            'from_name.required' => 'Tên người gửi là bắt buộc',
            'from_name.max' => 'Tên người gửi không được quá 255 ký tự',
            'is_active.boolean' => 'Trạng thái hoạt động không hợp lệ',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'mailer' => 'Loại mailer',
            'host' => 'Host',
            'port' => 'Port',
            'username' => 'Username',
            'password' => 'Password',
            'encryption' => 'Mã hóa',
            'from_address' => 'Email gửi',
            'from_name' => 'Tên người gửi',
            'is_active' => 'Trạng thái hoạt động',
        ];
    }
}