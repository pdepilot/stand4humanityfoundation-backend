<?php

namespace App\Http\Requests\Portal;

use App\Models\Testimony;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class StoreDeskTestimonyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to publish a testimony.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Testimony::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'role' => $this->filled('role') ? $this->input('role') : null,
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'role' => ['nullable', 'string', 'max:80'],
            'body' => ['required', 'string', 'min:20', 'max:1000'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'photo' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Enter the name to show.',
            'body.required' => 'Write the testimony.',
            'body.min' => 'Write at least a few sentences.',
            'rating.required' => 'Choose a rating.',
            'rating.between' => 'Choose a rating from 1 to 5.',
            'photo.image' => 'The portrait must be an image file.',
            'photo.mimes' => 'Upload a JPG, PNG, or WEBP portrait.',
            'photo.max' => 'The portrait must be 5 MB or smaller.',
        ];
    }

    public function uploadedPhoto(): ?UploadedFile
    {
        $photo = $this->file('photo');

        return $photo instanceof UploadedFile ? $photo : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function testimonyDetails(): array
    {
        return $this->safe()->only([
            'name',
            'role',
            'body',
            'rating',
        ]);
    }
}
