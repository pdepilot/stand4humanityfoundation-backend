<?php

namespace App\Enums;

enum ContactStatus: string
{
    case New = 'new';
    case Read = 'read';
    case Replied = 'replied';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Read => 'Read',
            self::Replied => 'Replied',
            self::Closed => 'Closed',
        };
    }
}
