<?php

namespace App\Enums;

enum EventAction: string
{
    case Contact = 'contact';
    case Volunteer = 'volunteer';
    case Services = 'services';
    case Mission = 'mission';
    case Donate = 'donate';

    public function routeName(): string
    {
        return match ($this) {
            self::Contact => 'contact',
            self::Volunteer => 'volunteer',
            self::Services => 'services',
            self::Mission => 'mission',
            self::Donate => 'donate',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Contact => 'Contact',
            self::Volunteer => 'Volunteer',
            self::Services => 'Services',
            self::Mission => 'Our Mission',
            self::Donate => 'Donate',
        };
    }
}
