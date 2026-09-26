<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreFoundationMemberPhotoAction
{
    /**
     * Store a member portrait on the public disk.
     */
    public function store(UploadedFile $file): string
    {
        return $file->store('members', 'public');
    }

    /**
     * Remove a previously uploaded portrait when it lives on the public disk.
     */
    public function delete(?string $path): void
    {
        if (! is_string($path) || $path === '' || ! str_starts_with($path, 'members/')) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
