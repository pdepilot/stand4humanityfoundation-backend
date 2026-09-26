<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreNewsletterSubscriberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge([
                'email' => strtolower(trim($this->input('email'))),
            ]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'website' => ['nullable', 'string', 'max:0'],
            'email' => ['required', 'string', 'email', 'max:120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'website.max' => 'Unable to accept this subscription.',
            'email.required' => 'Enter your email address.',
            'email.email' => 'Enter a valid email address.',
        ];
    }

    /**
     * @return array{email: string}
     */
    public function subscriberDetails(): array
    {
        return [
            'email' => $this->validated('email'),
        ];
    }
}
