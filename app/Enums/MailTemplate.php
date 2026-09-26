<?php

namespace App\Enums;

enum MailTemplate: string
{
    case ProgrammeNote = 'programme-note';
    case EventInvitation = 'event-invitation';
    case ThankYou = 'thank-you';
    case VolunteerCall = 'volunteer-call';

    public function label(): string
    {
        return match ($this) {
            self::ProgrammeNote => 'Programme note',
            self::EventInvitation => 'Event invitation',
            self::ThankYou => 'Thank you',
            self::VolunteerCall => 'Volunteer call',
        };
    }

    public function subject(): string
    {
        return match ($this) {
            self::ProgrammeNote => 'A note from Stand For Humanity Foundation',
            self::EventInvitation => 'You are invited to stand with us',
            self::ThankYou => 'Thank you for standing with people in need',
            self::VolunteerCall => 'We would be glad to have you with us',
        };
    }

    public function body(): string
    {
        return match ($this) {
            self::ProgrammeNote => "We are writing with a short update from Owerri.\n\nThe foundation continues to protect children, stand with people who have been harmed, and help families who cannot stand alone. Your attention makes that work visible.\n\nIf you need help, or know someone who does, reply to this note or visit the contact desk.",
            self::EventInvitation => "We would be glad to see you at an upcoming gathering.\n\nThese meetings are for families, neighbours, and volunteers. They are a chance to learn how the foundation protects children and supports people in need across Imo State.\n\nDetails are on the events page. Come as you are.",
            self::ThankYou => "Thank you for standing with Stand For Humanity Foundation.\n\nA gift, a visit, or a kind word reaches further than it feels. Because of people like you, a child can be safer and a family can ask for help without shame.\n\nWe will keep the work careful, and we will keep you informed.",
            self::VolunteerCall => "There is room for another pair of hands.\n\nVolunteers help with awareness, registration, and the quiet work of making a gathering feel safe. You do not need a title. You need a willing hour.\n\nIf this is your moment, the volunteer desk is open.",
        };
    }
}
