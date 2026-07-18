<?php

namespace App\Http\Requests\Admin\Employee;

use App\Enums\AdminRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employee = $this->route('employee');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($employee)],
            'mobile' => ['nullable', 'string', 'max:20', Rule::unique('admins', 'mobile')->ignore($employee)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => [Rule::in(AdminRole::allPermissionKeys())],
        ];
    }
}
