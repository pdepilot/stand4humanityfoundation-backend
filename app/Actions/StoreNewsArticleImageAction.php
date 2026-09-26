<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class StoreNewsArticleImageAction
{
    /**
     * Store a news cover on the public disk.
     */
    public function store(UploadedFile $file): string
    {
        return $file->store('news', 'public');
    }

    /**
     * Remove a previously uploaded news cover when it lives on the public disk.
     */
    public function delete(?string $path): void
    {
        if (! is_string($path) || $path === '' || ! str_starts_with($path, 'news/')) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
