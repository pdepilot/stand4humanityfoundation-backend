<?php

namespace App\Models;

use Database\Factories\NewsArticleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'slug',
    'title',
    'excerpt',
    'body',
    'image',
    'image_alt',
    'author',
    'reading_minutes',
    'tags',
    'search_keywords',
    'is_published',
    'is_popular',
    'published_at',
])]
class NewsArticle extends Model
{
    /** @use HasFactory<NewsArticleFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'author' => 'Foundation desk',
        'reading_minutes' => 4,
        'is_published' => true,
        'is_popular' => false,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'reading_minutes' => 'integer',
            'is_published' => 'boolean',
            'is_popular' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @param  Builder<NewsArticle>  $query
     * @return Builder<NewsArticle>
     */
    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    /**
     * @param  Builder<NewsArticle>  $query
     * @return Builder<NewsArticle>
     */
    #[Scope]
    protected function popular(Builder $query): Builder
    {
        return $query->where('is_popular', true);
    }

    public function imageUrl(): string
    {
        if ($this->hasUploadedImage()) {
            /** @var FilesystemAdapter $disk */
            $disk = Storage::disk('public');

            return $disk->url($this->image);
        }

        return asset('Charitize/img/'.$this->image);
    }

    public function hasUploadedImage(): bool
    {
        return is_string($this->image) && str_starts_with($this->image, 'news/');
    }

    public function metaLine(string $dateFormat = 'd M Y'): string
    {
        $date = $this->published_at?->timezone(config('app.timezone'))->format($dateFormat) ?? '';

        return trim($date.' · '.$this->reading_minutes.' min read · '.$this->author, ' ·');
    }

    public function renderedBody(): string
    {
        $html = Str::markdown($this->body, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $html = str_replace('<blockquote>', '<blockquote class="news-pullquote">', $html);

        return preg_replace(
            '/<p><em>(.*?)<\/em><\/p>\s*$/s',
            '<p class="news-note mb-0">$1</p>',
            $html,
        ) ?? $html;
    }

    public function articleMetaLine(): string
    {
        $date = $this->published_at?->timezone(config('app.timezone'))->format('d F Y') ?? '';

        return trim($date.' · Stand For Humanity Foundation · '.$this->reading_minutes.' min read', ' ·');
    }

    public function headerLabel(): string
    {
        $tags = $this->tagList();

        return $tags[0] ?? 'Programme Note';
    }

    public function wordCount(): int
    {
        return str_word_count(strip_tags(str_replace(["\n", '#'], ' ', $this->body)));
    }

    public function isSubstantialForPublishing(): bool
    {
        return $this->wordCount() >= 180
            && strlen(trim($this->excerpt)) >= 40
            && filled($this->image_alt);
    }

    /**
     * @return list<string>
     */
    public function tagList(): array
    {
        $tags = $this->tags;

        if (! is_array($tags)) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn (mixed $tag): string => is_string($tag) ? trim($tag) : '',
            $tags,
        )));
    }

    public function searchHaystack(): string
    {
        return strtolower(trim(implode(' ', array_filter([
            $this->title,
            $this->excerpt,
            $this->search_keywords,
            implode(' ', $this->tagList()),
        ]))));
    }
}
