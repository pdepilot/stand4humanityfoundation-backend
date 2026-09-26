<?php

namespace App\Enums;

enum TestimonyStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Declined = 'declined';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending review',
            self::Approved => 'Approved',
            self::Declined => 'Declined',
        };
    }
}
