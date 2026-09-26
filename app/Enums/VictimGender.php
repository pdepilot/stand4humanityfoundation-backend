<?php

namespace App\Enums;

enum VictimGender: string
{
    case Female = 'Female';
    case Male = 'Male';
    case Other = 'Other';
    case PreferNotToSay = 'Prefer not to say';

    public function label(): string
    {
        return $this->value;
    }
}
