<?php

namespace App\Enums;

enum VolunteerStatus: string
{
    case Pending = 'pending';
    case Reviewing = 'reviewing';
    case Approved = 'approved';
    case Declined = 'declined';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending review',
            self::Reviewing => 'Under review',
            self::Approved => 'Approved',
            self::Declined => 'Declined',
        };
    }

    public function isTerminal(): bool
    {
        return $this === self::Approved || $this === self::Declined;
    }
}
