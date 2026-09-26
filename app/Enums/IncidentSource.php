<?php

namespace App\Enums;

enum IncidentSource: string
{
    case Public = 'public';
    case Desk = 'desk';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Report Live',
            self::Desk => 'Command desk',
        };
    }
}
