<?php

namespace App\Models;

use App\Enums\EventAction;
use Database\Factories\FoundationEventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'slug',
    'title',
    'summary',
    'description',
    'image',
    'image_alt',
    'location',
    'audience',
    'highlights',
    'action',
    'starts_at',
    'ends_at',
    'is_featured',
    'is_published',
])]
class FoundationEvent extends Model
{
    /** @use HasFactory<FoundationEventFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'action' => EventAction::Contact->value,
        'is_featured' => false,
        'is_published' => true,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'highlights' => 'array',
            'action' => EventAction::class,
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @param  Builder<FoundationEvent>  $query
     * @return Builder<FoundationEvent>
     */
    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('is_published', true);
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
        return is_string($this->image) && str_starts_with($this->image, 'events/');
    }

    public function actionUrl(): string
    {
        return route($this->action->routeName());
    }

    public function timeRange(): string
    {
        $start = $this->localStartsAt()->format('h:i A');

        if ($this->ends_at === null) {
            return $start;
        }

        return $start.' – '.$this->ends_at->timezone(config('app.timezone'))->format('h:i A');
    }

    public function cardDate(): string
    {
        return $this->localStartsAt()->format('D, j M Y');
    }

    public function featuredDate(): string
    {
        return $this->localStartsAt()->format('l, j F Y');
    }

    public function dayNumber(): string
    {
        return $this->localStartsAt()->format('j');
    }

    public function monthLabel(): string
    {
        return $this->localStartsAt()->format('M');
    }

    /**
     * @return list<string>
     */
    public function highlightList(): array
    {
        $highlights = $this->highlights;

        if (! is_array($highlights)) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn (mixed $highlight): string => is_string($highlight) ? trim($highlight) : '',
            $highlights,
        )));
    }

    private function localStartsAt(): Carbon
    {
        return $this->starts_at->timezone(config('app.timezone'));
    }
}
