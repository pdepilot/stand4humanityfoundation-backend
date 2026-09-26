<?php

namespace App\Enums;

enum MailAudience: string
{
    case Individual = 'individual';
    case Subscribers = 'subscribers';
    case Donors = 'donors';
    case Volunteers = 'volunteers';
    case Contacts = 'contacts';

    public function label(): string
    {
        return match ($this) {
            self::Individual => 'One person',
            self::Subscribers => 'Newsletter subscribers',
            self::Donors => 'Donors',
            self::Volunteers => 'Volunteers',
            self::Contacts => 'Contact desk',
        };
    }
}
