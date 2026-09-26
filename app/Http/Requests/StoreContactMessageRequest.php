<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
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
        $nullable = [];

        foreach (['phone'] as $field) {
            if ($this->input($field) === '') {
                $nullable[$field] = null;
            }
        }

        $this->merge($nullable);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'website' => ['nullable', 'string', 'max:0'],
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'website.max' => 'Unable to accept this message.',
            'name.required' => 'Enter your name.',
            'email.required' => 'Enter your email address.',
            'email.email' => 'Enter a valid email address.',
            'subject.required' => 'Enter the type of assistance you need.',
            'message.required' => 'Describe your request.',
            'message.min' => 'Describe your request in at least 10 characters.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function contactDetails(): array
    {
        return $this->safe()->only([
            'name',
            'email',
            'phone',
            'subject',
            'message',
        ]);
    }
}
