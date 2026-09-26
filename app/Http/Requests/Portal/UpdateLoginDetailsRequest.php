<?php

namespace App\Http\Requests\Portal;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateLoginDetailsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user?->can('updateLoginDetails', $user) ?? false;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('password') === '') {
            $this->merge(['password' => null]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()?->id),
            ],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Enter your current password.',
            'current_password.current_password' => 'That current password is not correct.',
            'email.required' => 'Enter the new login email.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'That email is already in use.',
            'password.confirmed' => 'The new password confirmation does not match.',
            'password.min' => 'Use at least 8 characters for the new password.',
        ];
    }

    /**
     * @return array{email: string, password?: string}
     */
    public function loginDetails(): array
    {
        $details = [
            'email' => $this->validated('email'),
        ];

        if ($this->filled('password')) {
            $details['password'] = $this->validated('password');
        }

        return $details;
    }
}
