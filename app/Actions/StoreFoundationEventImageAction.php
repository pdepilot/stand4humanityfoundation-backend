<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreFoundationEventImageAction
{
    /**
     * Store an event image on the public disk.
     */
    public function store(UploadedFile $file): string
    {
        return $file->store('events', 'public');
    }

    /**
     * Remove a previously uploaded event image when it lives on the public disk.
     */
    public function delete(?string $path): void
    {
        if (! is_string($path) || $path === '' || ! str_starts_with($path, 'events/')) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
