<?php

namespace App\Enums;

enum IncidentRelationship: string
{
    case Victim = 'Victim';
    case Witness = 'Witness';
    case Relative = 'Relative';
    case Volunteer = 'Volunteer';
    case CommunityMember = 'Community Member';
    case AnonymousReporter = 'Anonymous Reporter';

    public function label(): string
    {
        return $this->value;
    }

    public function isAnonymous(): bool
    {
        return $this === self::AnonymousReporter;
    }
}
