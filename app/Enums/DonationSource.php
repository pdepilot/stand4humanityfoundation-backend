<?php

namespace App\Enums;

enum DonationSource: string
{
    case Public = 'public';
    case Desk = 'desk';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Public donate page',
            self::Desk => 'Command desk',
        };
    }
}
