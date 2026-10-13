<?php

namespace App\Http\Requests\Portal;

use App\Models\TeamMember;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class StoreTeamMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to add a team member.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', TeamMember::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'facebook_url' => $this->filled('facebook_url') ? $this->input('facebook_url') : null,
            'x_url' => $this->filled('x_url') ? $this->input('x_url') : null,
            'instagram_url' => $this->filled('instagram_url') ? $this->input('instagram_url') : null,
            'youtube_url' => $this->filled('youtube_url') ? $this->input('youtube_url') : null,
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:160'],
            'role' => ['required', 'string', 'max:160'],
            'photo' => ['required', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'x_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Enter the team member’s name.',
            'role.required' => 'Enter their position.',
            'photo.required' => 'Upload a portrait.',
            'photo.image' => 'The portrait must be an image file.',
            'photo.mimes' => 'Upload a JPG, PNG, or WEBP portrait.',
            'photo.max' => 'The portrait must be 5 MB or smaller.',
            'facebook_url.url' => 'Enter a full Facebook address, including https://.',
            'x_url.url' => 'Enter a full X address, including https://.',
            'instagram_url.url' => 'Enter a full Instagram address, including https://.',
            'youtube_url.url' => 'Enter a full YouTube address, including https://.',
        ];
    }

    public function uploadedPhoto(): UploadedFile
    {
        /** @var UploadedFile $photo */
        $photo = $this->file('photo');

        return $photo;
    }

    /**
     * @return array<string, mixed>
     */
    public function teamMemberDetails(): array
    {
        return $this->safe()->only([
            'name',
            'role',
            'facebook_url',
            'x_url',
            'instagram_url',
            'youtube_url',
        ]);
    }
}
