<?php

namespace App\Enums;

enum VolunteerInterest: string
{
    case ChildProtection = 'child_protection';
    case VictimSupport = 'victim_support';
    case CommunityAwareness = 'community_awareness';
    case EmergencyResponse = 'emergency_response';
    case General = 'general';

    public function label(): string
    {
        return match ($this) {
            self::ChildProtection => 'Child protection',
            self::VictimSupport => 'Victim support & advocacy',
            self::CommunityAwareness => 'Community awareness',
            self::EmergencyResponse => 'Emergency response',
            self::General => 'General volunteering',
        };
    }
}
