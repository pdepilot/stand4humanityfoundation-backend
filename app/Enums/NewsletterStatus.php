<?php

namespace App\Enums;

enum NewsletterStatus: string
{
    case Active = 'active';
    case Unsubscribed = 'unsubscribed';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Subscribed',
            self::Unsubscribed => 'Unsubscribed',
        };
    }

    public function isActive(): bool
    {
        return $this === self::Active;
    }
}
