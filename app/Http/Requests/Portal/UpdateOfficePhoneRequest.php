<?php

namespace App\Http\Requests\Portal;

use App\Enums\PortalPermission;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOfficePhoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to change the public phone number.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user instanceof User && $user->canOpenPortal(PortalPermission::Settings);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'max:40'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Enter the public phone number.',
            'phone.max' => 'The phone number must be 40 characters or fewer.',
        ];
    }
}
