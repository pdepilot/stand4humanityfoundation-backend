<?php

namespace App\Http\Requests\Portal;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreAdminUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', User::class) ?? false;
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
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            'admin_role_id' => ['nullable', 'integer', Rule::exists('admin_roles', 'id')],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Enter the administrator name.',
            'email.required' => 'Enter an email address.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'That email is already in use.',
            'password.required' => 'Enter a password.',
            'password.confirmed' => 'The password confirmation does not match.',
            'admin_role_id.exists' => 'Choose a role from the list.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function administratorDetails(): array
    {
        return [
            ...$this->safe()->only(['name', 'email', 'password', 'admin_role_id']),
            'role' => UserRole::Administrator,
            'email_verified_at' => now(),
        ];
    }
}
