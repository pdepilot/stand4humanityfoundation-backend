<?php

namespace App\Enums;

enum VolunteerSource: string
{
    case Public = 'public';
    case Desk = 'desk';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Volunteer page',
            self::Desk => 'Command desk',
        };
    }
}
