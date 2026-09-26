<?php

namespace App\Http\Requests\Portal;

use App\Enums\EventAction;
use App\Models\FoundationEvent;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateFoundationEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $event = $this->route('foundationEvent');

        return $event instanceof FoundationEvent
            && ($this->user()?->can('update', $event) ?? false);
    }

    protected function prepareForValidation(): void
    {
        $slug = $this->input('slug');

        if ((! is_string($slug) || trim($slug) === '') && is_string($this->input('title'))) {
            $slug = Str::slug($this->input('title'));
        }

        $this->merge([
            'slug' => is_string($slug) ? Str::slug($slug) : $slug,
            'highlights' => $this->highlightLines(),
            'audience' => $this->filled('audience') ? $this->input('audience') : null,
            'is_published' => $this->boolean('is_published'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        $event = $this->route('foundationEvent');

        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => [
                'required',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('foundation_events', 'slug')->ignore($event instanceof FoundationEvent ? $event->id : null),
            ],
            'summary' => ['required', 'string', 'max:400'],
            'description' => ['required', 'string', 'max:2000'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'image_alt' => ['required', 'string', 'max:200'],
            'location' => ['required', 'string', 'max:160'],
            'audience' => ['nullable', 'string', 'max:80'],
            'highlights' => ['nullable', 'array', 'max:6'],
            'highlights.*' => ['string', 'max:160'],
            'action' => ['required', Rule::enum(EventAction::class)],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_published' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Enter the event title.',
            'slug.required' => 'Enter a URL slug.',
            'slug.unique' => 'That slug is already in use.',
            'slug.regex' => 'Use lowercase letters, numbers, and hyphens only.',
            'summary.required' => 'Enter a short summary.',
            'description.required' => 'Describe the gathering.',
            'image.image' => 'The image must be an image file.',
            'image.mimes' => 'Upload a JPG, PNG, WEBP, or GIF image.',
            'image.max' => 'The image must be 5 MB or smaller.',
            'image_alt.required' => 'Describe the image for accessibility.',
            'location.required' => 'Enter the location.',
            'action.required' => 'Choose where the event title should link.',
            'starts_at.required' => 'Choose a start date and time.',
            'ends_at.after_or_equal' => 'The end time must be on or after the start time.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function eventDetails(): array
    {
        return $this->safe()->only([
            'title',
            'slug',
            'summary',
            'description',
            'image_alt',
            'location',
            'audience',
            'highlights',
            'action',
            'starts_at',
            'ends_at',
            'is_published',
            'is_featured',
        ]);
    }

    public function uploadedImage(): ?UploadedFile
    {
        $image = $this->file('image');

        return $image instanceof UploadedFile ? $image : null;
    }

    /**
     * @return list<string>
     */
    private function highlightLines(): array
    {
        $highlights = $this->input('highlights');

        if (! is_string($highlights)) {
            return [];
        }

        return collect(preg_split('/[\r\n,]+/', $highlights) ?: [])
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
