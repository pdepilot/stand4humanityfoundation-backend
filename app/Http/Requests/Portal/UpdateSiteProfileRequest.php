<?php

namespace App\Http\Requests\Portal;

use App\Models\SiteProfile;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UpdateSiteProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to change the public site.
     */
    public function authorize(): bool
    {
        $profile = SiteProfile::current();

        return $profile instanceof SiteProfile
            && ($this->user()?->can('update', $profile) ?? false);
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'x_url' => $this->filled('x_url') ? $this->input('x_url') : null,
            'facebook_url' => $this->filled('facebook_url') ? $this->input('facebook_url') : null,
            'instagram_url' => $this->filled('instagram_url') ? $this->input('instagram_url') : null,
            'youtube_url' => $this->filled('youtube_url') ? $this->input('youtube_url') : null,
            'linkedin_url' => $this->filled('linkedin_url') ? $this->input('linkedin_url') : null,
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'address' => ['required', 'string', 'max:160'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:120'],
            'x_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'weekday_label' => ['required', 'string', 'max:80'],
            'weekday_hours' => ['required', 'string', 'max:80'],
            'saturday_label' => ['required', 'string', 'max:80'],
            'saturday_hours' => ['required', 'string', 'max:80'],
            'extra_label' => ['required', 'string', 'max:80'],
            'extra_hours' => ['required', 'string', 'max:80'],
        ];

        foreach (range(1, 6) as $slot) {
            $rules['gallery_'.$slot] = ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'address.required' => 'Enter the office address.',
            'phone.required' => 'Enter the public phone number.',
            'email.required' => 'Enter the public email address.',
            'email.email' => 'Enter a valid email address.',
            'x_url.url' => 'Enter a full X address, including https://.',
            'facebook_url.url' => 'Enter a full Facebook address, including https://.',
            'instagram_url.url' => 'Enter a full Instagram address, including https://.',
            'youtube_url.url' => 'Enter a full YouTube address, including https://.',
            'linkedin_url.url' => 'Enter a full LinkedIn address, including https://.',
            'weekday_label.required' => 'Enter the weekday heading.',
            'weekday_hours.required' => 'Enter the weekday hours.',
            'saturday_label.required' => 'Enter the Saturday heading.',
            'saturday_hours.required' => 'Enter the Saturday hours.',
            'extra_label.required' => 'Enter the third hours heading.',
            'extra_hours.required' => 'Enter the third hours.',
            'gallery_1.image' => 'Each gallery picture must be an image file.',
            'gallery_1.mimes' => 'Upload a JPG, PNG, or WEBP picture.',
            'gallery_1.max' => 'Each gallery picture must be 5 MB or smaller.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $attributes = [];

        foreach (range(1, 6) as $slot) {
            $attributes['gallery_'.$slot] = 'gallery picture '.$slot;
        }

        return $attributes;
    }

    /**
     * @return array<string, mixed>
     */
    public function profileDetails(): array
    {
        return $this->safe()->only([
            'address',
            'phone',
            'email',
            'x_url',
            'facebook_url',
            'instagram_url',
            'youtube_url',
            'linkedin_url',
            'weekday_label',
            'weekday_hours',
            'saturday_label',
            'saturday_hours',
            'extra_label',
            'extra_hours',
        ]);
    }

    public function galleryFile(int $slot): ?UploadedFile
    {
        $file = $this->file('gallery_'.$slot);

        return $file instanceof UploadedFile ? $file : null;
    }
}
