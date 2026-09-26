<?php

namespace App\Http\Requests\Portal;

use App\Models\NewsArticle;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateNewsArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $article = $this->route('newsArticle');

        return $article instanceof NewsArticle
            && ($this->user()?->can('update', $article) ?? false);
    }

    protected function prepareForValidation(): void
    {
        $slug = $this->input('slug');

        if ((! is_string($slug) || trim($slug) === '') && is_string($this->input('title'))) {
            $slug = Str::slug($this->input('title'));
        }

        $tags = $this->input('tags');

        if (is_string($tags)) {
            $tags = collect(preg_split('/[,\\n]+/', $tags) ?: [])
                ->map(fn (string $tag): string => trim($tag))
                ->filter()
                ->values()
                ->all();
        }

        $this->merge([
            'slug' => is_string($slug) ? Str::slug($slug) : $slug,
            'tags' => $tags,
            'is_published' => $this->boolean('is_published'),
            'is_popular' => $this->boolean('is_popular'),
            'author' => $this->filled('author') ? $this->input('author') : 'Foundation desk',
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<int, mixed>|string>
     */
    public function rules(): array
    {
        $article = $this->route('newsArticle');

        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => [
                'required',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('news_articles', 'slug')->ignore($article instanceof NewsArticle ? $article->id : null),
            ],
            'excerpt' => ['required', 'string', 'max:400'],
            'body' => ['required', 'string', 'min:200'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'image_alt' => ['required', 'string', 'max:200'],
            'author' => ['required', 'string', 'max:80'],
            'reading_minutes' => ['required', 'integer', 'min:1', 'max:60'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:40'],
            'search_keywords' => ['nullable', 'string', 'max:500'],
            'is_published' => ['sometimes', 'boolean'],
            'is_popular' => ['sometimes', 'boolean'],
            'published_at' => ['required', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Enter the note title.',
            'slug.required' => 'Enter a URL slug.',
            'slug.unique' => 'That slug is already in use.',
            'slug.regex' => 'Use lowercase letters, numbers, and hyphens only.',
            'excerpt.required' => 'Enter a short excerpt.',
            'body.required' => 'Write the note body.',
            'body.min' => 'Write a substantial note (at least 200 characters). Google AdSense expects original, useful content.',
            'image.image' => 'The cover must be an image file.',
            'image.mimes' => 'Upload a JPG, PNG, WEBP, or GIF image.',
            'image.max' => 'The cover image must be 5 MB or smaller.',
            'image_alt.required' => 'Describe the image for accessibility.',
            'published_at.required' => 'Choose a publish date.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function articleDetails(): array
    {
        return $this->safe()->only([
            'title',
            'slug',
            'excerpt',
            'body',
            'image_alt',
            'author',
            'reading_minutes',
            'tags',
            'search_keywords',
            'is_published',
            'is_popular',
            'published_at',
        ]);
    }

    public function uploadedImage(): ?UploadedFile
    {
        $image = $this->file('image');

        return $image instanceof UploadedFile ? $image : null;
    }
}
