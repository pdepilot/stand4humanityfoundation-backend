<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdministrator = 'super-administrator';
    case Administrator = 'administrator';
    case Staff = 'staff';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdministrator => 'Super administrator',
            self::Administrator => 'Administrator',
            self::Staff => 'Staff workspace',
        };
    }
}
