<?php

namespace App\Http\Requests\Portal;

use App\Enums\MemberGender;
use App\Enums\MembershipStatus;
use App\Models\FoundationMember;
use App\Support\NigerianState;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class UpdateFoundationMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $member = $this->route('foundationMember');

        return $member instanceof FoundationMember
            && ($this->user()?->can('update', $member) ?? false);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => $this->filled('email') ? $this->input('email') : null,
            'occupation' => $this->filled('occupation') ? $this->input('occupation') : null,
            'state' => $this->filled('state') ? $this->input('state') : 'Imo',
            'emergency_contact_name' => $this->filled('emergency_contact_name') ? $this->input('emergency_contact_name') : null,
            'emergency_contact_phone' => $this->filled('emergency_contact_phone') ? $this->input('emergency_contact_phone') : null,
            'notes' => $this->filled('notes') ? $this->input('notes') : null,
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:160'],
            'photo' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'gender' => ['required', Rule::enum(MemberGender::class)],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:80'],
            'state' => ['required', 'string', Rule::in(NigerianState::names())],
            'occupation' => ['nullable', 'string', 'max:160'],
            'joined_on' => ['required', 'date', 'before_or_equal:today'],
            'status' => ['required', Rule::enum(MembershipStatus::class)],
            'emergency_contact_name' => ['nullable', 'string', 'max:160'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Enter the member’s full name.',
            'photo.image' => 'The photo must be an image file.',
            'photo.mimes' => 'Upload a JPG, PNG, or WEBP photo.',
            'photo.max' => 'The photo must be 5 MB or smaller.',
            'gender.required' => 'Choose a gender.',
            'date_of_birth.required' => 'Enter the date of birth.',
            'date_of_birth.before' => 'The date of birth must be in the past.',
            'phone.required' => 'Enter a phone number.',
            'address.required' => 'Enter the home address.',
            'city.required' => 'Enter the city.',
            'joined_on.required' => 'Enter the date they joined.',
            'joined_on.before_or_equal' => 'The join date cannot be in the future.',
            'status.required' => 'Choose a membership status.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function memberDetails(): array
    {
        return $this->safe()->only([
            'full_name',
            'gender',
            'date_of_birth',
            'phone',
            'email',
            'address',
            'city',
            'state',
            'occupation',
            'joined_on',
            'status',
            'emergency_contact_name',
            'emergency_contact_phone',
            'notes',
        ]);
    }

    public function uploadedPhoto(): ?UploadedFile
    {
        $photo = $this->file('photo');

        return $photo instanceof UploadedFile ? $photo : null;
    }
}
