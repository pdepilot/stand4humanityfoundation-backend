<?php

namespace App\Http\Requests;

use App\Enums\VolunteerInterest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class StoreVolunteerApplicationRequest extends FormRequest
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
        if ($this->input('experience') === '') {
            $this->merge(['experience' => null]);
        }
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
            'phone' => ['required', 'string', 'max:40'],
            'city' => ['required', 'string', 'max:80'],
            'interest' => ['required', 'string', Rule::enum(VolunteerInterest::class)],
            'availability' => ['required', 'string', 'max:120'],
            'experience' => ['nullable', 'string', 'max:3000'],
            'motivation' => ['required', 'string', 'min:20', 'max:5000'],
            'photo' => ['required', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'website.max' => 'Unable to accept this application.',
            'name.required' => 'Enter your name.',
            'email.required' => 'Enter your email address.',
            'email.email' => 'Enter a valid email address.',
            'phone.required' => 'Enter your phone number.',
            'city.required' => 'Enter your city or community.',
            'interest.required' => 'Choose the area you want to support.',
            'interest.Illuminate\Validation\Rules\Enum' => 'Choose a valid volunteer interest.',
            'availability.required' => 'Tell us when you can serve.',
            'motivation.required' => 'Tell us why you want to volunteer.',
            'motivation.min' => 'Write at least 20 characters about why you want to volunteer.',
            'photo.required' => 'Upload a clear photo of yourself.',
            'photo.image' => 'The photo must be an image file.',
            'photo.mimes' => 'Upload a JPG, PNG, or WEBP photo.',
            'photo.max' => 'The photo must be 5 MB or smaller.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function applicationDetails(): array
    {
        return $this->safe()->only([
            'name',
            'email',
            'phone',
            'city',
            'interest',
            'availability',
            'experience',
            'motivation',
        ]);
    }

    public function uploadedPhoto(): UploadedFile
    {
        /** @var UploadedFile $photo */
        $photo = $this->file('photo');

        return $photo;
    }
}
