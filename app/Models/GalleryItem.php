<?php

namespace App\Models;

use App\Enums\GalleryMedia;
use Database\Factories\GalleryItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'title',
    'details',
    'kind',
    'path',
])]
class GalleryItem extends Model
{
    /** @use HasFactory<GalleryItemFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kind' => GalleryMedia::class,
        ];
    }

    public function isVideo(): bool
    {
        return $this->kind === GalleryMedia::Video;
    }

    /**
     * Public address for an uploaded picture or video.
     */
    public function mediaUrl(): string
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->path);
    }

    public function mimeType(): string
    {
        if (str_ends_with(strtolower($this->path), '.webm')) {
            return 'video/webm';
        }

        return 'video/mp4';
    }
}
