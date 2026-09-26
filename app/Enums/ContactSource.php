<?php

namespace App\Enums;

enum ContactSource: string
{
    case Public = 'public';
    case Desk = 'desk';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Public contact page',
            self::Desk => 'Command desk',
        };
    }
}
