<?php

namespace App\Http\Requests\Portal;

use App\Enums\VolunteerStatus;
use App\Models\VolunteerApplication;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVolunteerApplicationStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $application = $this->route('volunteerApplication');

        return $application instanceof VolunteerApplication
            && ($this->user()?->can('update', $application) ?? false);
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('admin_notes') === '') {
            $this->merge(['admin_notes' => null]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::enum(VolunteerStatus::class)],
            'admin_notes' => ['nullable', 'string', 'max:3000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Choose a vetting status.',
        ];
    }

    public function status(): VolunteerStatus
    {
        return VolunteerStatus::from($this->validated('status'));
    }

    public function adminNotes(): ?string
    {
        $notes = $this->validated('admin_notes');

        return is_string($notes) ? $notes : null;
    }
}
