<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreSiteGalleryAction
{
    /**
     * Store a footer gallery picture on the public disk.
     */
    public function store(UploadedFile $file): string
    {
        return $file->store('site', 'public');
    }

    /**
     * Remove a previously uploaded gallery picture when it lives on the public disk.
     */
    public function delete(?string $path): void
    {
        if (! is_string($path) || $path === '' || ! str_starts_with($path, 'site/')) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
