<?php

namespace App\Enums;

enum NewsletterSource: string
{
    case Public = 'public';
    case Desk = 'desk';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Public site',
            self::Desk => 'Command desk',
        };
    }
}
