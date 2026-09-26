<?php

namespace App\Enums;

enum DonationFrequency: string
{
    case OneTime = 'one-time';
    case Monthly = 'monthly';
    case Quarterly = 'quarterly';
    case Yearly = 'yearly';

    public function label(): string
    {
        return match ($this) {
            self::OneTime => 'One-time',
            self::Monthly => 'Monthly',
            self::Quarterly => 'Quarterly',
            self::Yearly => 'Yearly',
        };
    }
}
