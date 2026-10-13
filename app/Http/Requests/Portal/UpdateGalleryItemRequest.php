<?php

namespace App\Http\Requests\Portal;

use App\Enums\GalleryMedia;
use App\Models\GalleryItem;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class UpdateGalleryItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to change a gallery item.
     */
    public function authorize(): bool
    {
        $item = $this->route('galleryItem');

        return $item instanceof GalleryItem
            && ($this->user()?->can('update', $item) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:160'],
            'details' => ['required', 'string', 'max:2000'],
            'kind' => ['required', Rule::enum(GalleryMedia::class)],
            'media' => [
                'nullable',
                'file',
                Rule::when($this->input('kind') === GalleryMedia::Image->value, ['image', 'mimes:jpeg,jpg,png,webp', 'max:5120']),
                Rule::when($this->input('kind') === GalleryMedia::Video->value, ['mimes:mp4,webm', 'max:15360']),
            ],
        ];
    }

    /**
     * @return array<int, \Closure>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $item = $this->route('galleryItem');

                if (! $item instanceof GalleryItem) {
                    return;
                }

                if ($this->input('kind') !== $item->kind->value && ! $this->hasFile('media')) {
                    $validator->errors()->add('media', 'Upload a new file when you change the type.');
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        $video = $this->input('kind') === GalleryMedia::Video->value;

        return [
            'title.required' => 'Enter a title.',
            'details.required' => 'Enter the details.',
            'kind.required' => 'Choose a picture or a video.',
            'media.image' => 'Upload a JPG, PNG, or WEBP picture.',
            'media.mimes' => $video ? 'Upload an MP4 or WEBM video.' : 'Upload a JPG, PNG, or WEBP picture.',
            'media.max' => $video ? 'The video must be 15 MB or smaller.' : 'The picture must be 5 MB or smaller.',
        ];
    }

    public function uploadedMedia(): ?UploadedFile
    {
        $media = $this->file('media');

        return $media instanceof UploadedFile ? $media : null;
    }

    /**
     * @return array<string, mixed>
     */
    public function itemDetails(): array
    {
        return $this->safe()->only([
            'title',
            'details',
            'kind',
        ]);
    }
}
