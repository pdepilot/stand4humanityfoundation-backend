<?php

namespace App\Http\Requests\Portal;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignAdminRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to assign a role to an administrator.
     */
    public function authorize(): bool
    {
        $user = $this->route('user');

        return $user instanceof User
            && ($this->user()?->can('assignRole', $user) ?? false);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'admin_role_id' => $this->filled('admin_role_id') ? $this->input('admin_role_id') : null,
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'admin_role_id' => ['nullable', 'integer', Rule::exists('admin_roles', 'id')],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'admin_role_id.exists' => 'Choose a role from the list.',
        ];
    }
}
