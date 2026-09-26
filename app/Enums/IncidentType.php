<?php

namespace App\Enums;

enum IncidentType: string
{
    case MissingPerson = 'Missing Person';
    case Kidnapping = 'Kidnapping';
    case HumanRightsAbuse = 'Human Rights Abuse';
    case Harassment = 'Harassment';
    case DomesticViolence = 'Domestic Violence';
    case ChildAbuse = 'Child Abuse';
    case HumanTrafficking = 'Human Trafficking';
    case SexualViolence = 'Sexual Violence';
    case CommunitySecurityThreat = 'Community Security Threat';
    case ThreatsOrIntimidation = 'Threats or Intimidation';
    case FraudOrScam = 'Fraud or Scam';
    case Cybercrime = 'Cybercrime';
    case Other = 'Other';

    public function label(): string
    {
        return $this->value;
    }
}
