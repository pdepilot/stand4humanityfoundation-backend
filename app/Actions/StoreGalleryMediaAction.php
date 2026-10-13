<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreGalleryMediaAction
{
    /**
     * Store a gallery picture or video on the public disk.
     */
    public function store(UploadedFile $file): string
    {
        return $file->store('gallery', 'public');
    }

    /**
     * Remove a previously uploaded gallery file when it lives on the public disk.
     */
    public function delete(?string $path): void
    {
        if (! is_string($path) || $path === '' || ! str_starts_with($path, 'gallery/')) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
