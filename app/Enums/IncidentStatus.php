<?php

namespace App\Enums;

enum IncidentStatus: string
{
    case Submitted = 'submitted';
    case UnderReview = 'under-review';
    case InvestigationOngoing = 'investigation-ongoing';
    case Referred = 'referred';
    case Resolved = 'resolved';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Submitted',
            self::UnderReview => 'Under Review',
            self::InvestigationOngoing => 'Investigation Ongoing',
            self::Referred => 'Referred',
            self::Resolved => 'Resolved',
            self::Closed => 'Closed',
        };
    }
}
