<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreVolunteerPhotoAction
{
    /**
     * Store a volunteer applicant photo on the public disk.
     */
    public function store(UploadedFile $file): string
    {
        return $file->store('volunteers', 'public');
    }

    /**
     * Remove a previously uploaded volunteer photo when it lives on the public disk.
     */
    public function delete(?string $path): void
    {
        if (! is_string($path) || $path === '' || ! str_starts_with($path, 'volunteers/')) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
