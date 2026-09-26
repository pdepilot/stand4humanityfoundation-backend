<?php

namespace App\Enums;

use LogicException;

enum DonationCause: string
{
    case ChildProtection = 'child-protection';
    case Rescue = 'rescue';
    case VictimSupport = 'victim-support';
    case CommunityAwareness = 'community-awareness';
    case General = 'general';

    public function label(): string
    {
        return match ($this) {
            self::ChildProtection => 'Child Protection & Rescue',
            self::Rescue => 'Emergency Rescue & Intervention',
            self::VictimSupport => 'Victim Support & Advocacy',
            self::CommunityAwareness => 'Community Awareness & Advocacy',
            self::General => 'General Donation',
        };
    }

    /**
     * Programme cards on the public donate and home pages.
     *
     * @return list<self>
     */
    public static function publicGoalCauses(): array
    {
        return [
            self::ChildProtection,
            self::Rescue,
            self::VictimSupport,
        ];
    }

    /**
     * Column that stores this cause's public donation goal.
     */
    public function goalColumn(): string
    {
        return match ($this) {
            self::ChildProtection => 'child_protection_goal',
            self::Rescue => 'rescue_goal',
            self::VictimSupport => 'victim_support_goal',
            default => throw new LogicException('This cause does not have a public donation goal.'),
        };
    }
}
