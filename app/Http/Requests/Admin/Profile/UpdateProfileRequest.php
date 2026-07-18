<?php

namespace App\Http\Requests\Admin\Profile;

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
            'email' => ['required', 'email', Rule::unique('admins', 'email')->ignore($this->user('admin')->id)],
            'mobile' => ['nullable', 'string', 'max:20', Rule::unique('admins', 'mobile')->ignore($this->user('admin')->id)],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
