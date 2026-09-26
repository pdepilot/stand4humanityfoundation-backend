<?php

namespace App\Enums;

enum MemberGender: string
{
    case Female = 'female';
    case Male = 'male';
    case Unspecified = 'unspecified';

    public function label(): string
    {
        return match ($this) {
            self::Female => 'Female',
            self::Male => 'Male',
            self::Unspecified => 'Prefer not to say',
        };
    }
}
