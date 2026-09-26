<?php

namespace App\Http\Requests\Portal;

use App\Enums\ContactStatus;
use App\Models\ContactMessage;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactMessageStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $message = $this->route('contactMessage');

        return $message instanceof ContactMessage
            && ($this->user()?->can('update', $message) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::enum(ContactStatus::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Choose a message status.',
        ];
    }

    public function status(): ContactStatus
    {
        return ContactStatus::from($this->validated('status'));
    }
}
