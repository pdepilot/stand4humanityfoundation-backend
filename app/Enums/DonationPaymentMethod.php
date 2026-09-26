<?php

namespace App\Enums;

enum DonationPaymentMethod: string
{
    case Paystack = 'paystack';
    case Flutterwave = 'flutterwave';
    case Cash = 'cash';
    case Transfer = 'transfer';

    public function label(): string
    {
        return match ($this) {
            self::Paystack => 'Paystack',
            self::Flutterwave => 'Flutterwave',
            self::Cash => 'Cash',
            self::Transfer => 'Transfer',
        };
    }
}
