<?php

namespace App\Enums;

enum DonationCurrency: string
{
    case Ngn = 'NGN';
    case Usd = 'USD';
    case Gbp = 'GBP';
    case Eur = 'EUR';

    public function label(): string
    {
        return match ($this) {
            self::Ngn => 'NGN (₦)',
            self::Usd => 'USD ($)',
            self::Gbp => 'GBP (£)',
            self::Eur => 'EUR (€)',
        };
    }

    /**
     * Display rate used to express this currency as Nigerian naira.
     */
    public function ngnRate(): float
    {
        return match ($this) {
            self::Ngn => 1.0,
            self::Usd => 1600.0,
            self::Gbp => 2050.0,
            self::Eur => 1750.0,
        };
    }
}
