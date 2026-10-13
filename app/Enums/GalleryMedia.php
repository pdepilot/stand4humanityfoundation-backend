<?php

namespace App\Enums;

enum GalleryMedia: string
{
    case Image = 'image';
    case Video = 'video';

    public function label(): string
    {
        return match ($this) {
            self::Image => 'Picture',
            self::Video => 'Video',
        };
    }
}
