<?php

namespace App\Http\Requests\Portal;

use App\Enums\PortalPermission;
use App\Models\AdminRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to change an administrator role.
     */
    public function authorize(): bool
    {
        $role = $this->route('adminRole');

        return $role instanceof AdminRole
            && ($this->user()?->can('update', $role) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        $role = $this->route('adminRole');

        return [
            'name' => ['required', 'string', 'max:80', Rule::unique('admin_roles', 'name')->ignore($role instanceof AdminRole ? $role->id : null)],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['required', Rule::enum(PortalPermission::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Enter a role name.',
            'name.unique' => 'That role name is already in use.',
            'permissions.required' => 'Choose at least one page.',
            'permissions.min' => 'Choose at least one page.',
            'permissions.*.enum' => 'Choose a page from the list.',
        ];
    }

    /**
     * @return array{name: string, permissions: list<string>}
     */
    public function roleDetails(): array
    {
        /** @var list<string> $permissions */
        $permissions = array_values($this->validated('permissions'));

        return [
            'name' => $this->validated('name'),
            'permissions' => $permissions,
        ];
    }
}
