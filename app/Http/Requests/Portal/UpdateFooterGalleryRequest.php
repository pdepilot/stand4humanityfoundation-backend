<?php

namespace App\Http\Requests\Portal;

use App\Models\SiteProfile;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UpdateFooterGalleryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to change the footer pictures.
     */
    public function authorize(): bool
    {
        $profile = SiteProfile::current();

        return $profile instanceof SiteProfile
            && ($this->user()?->can('update', $profile) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        $rules = [];

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
            'gallery_1.image' => 'Each footer picture must be an image file.',
            'gallery_1.mimes' => 'Upload a JPG, PNG, or WEBP picture.',
            'gallery_1.max' => 'Each footer picture must be 5 MB or smaller.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        $attributes = [];

        foreach (range(1, 6) as $slot) {
            $attributes['gallery_'.$slot] = 'footer picture '.$slot;
        }

        return $attributes;
    }

    public function galleryFile(int $slot): ?UploadedFile
    {
        $file = $this->file('gallery_'.$slot);

        return $file instanceof UploadedFile ? $file : null;
    }
}
