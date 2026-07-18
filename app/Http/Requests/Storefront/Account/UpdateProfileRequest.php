<?php

namespace App\Http\Requests\Storefront\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', Rule::unique('customers', 'email')->ignore($this->user('customer')->id)],
            'mobile' => ['nullable', 'string', 'max:20', Rule::unique('customers', 'mobile')->ignore($this->user('customer')->id)],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
