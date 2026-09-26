<?php

namespace App\Enums;

enum AuditReport: string
{
    case Cases = 'cases';
    case Donations = 'donations';
    case Members = 'members';
    case Attendance = 'attendance';
    case Contacts = 'contacts';
    case Volunteers = 'volunteers';
    case Subscribers = 'subscribers';
    case Mail = 'mail';

    public function label(): string
    {
        return match ($this) {
            self::Cases => 'Case reports',
            self::Donations => 'Donations',
            self::Members => 'Members',
            self::Attendance => 'Meeting attendance',
            self::Contacts => 'Contact messages',
            self::Volunteers => 'Volunteer applications',
            self::Subscribers => 'Newsletter subscribers',
            self::Mail => 'Outbound mail',
        };
    }

    public function dateColumn(): string
    {
        return match ($this) {
            self::Attendance => 'meeting_on',
            self::Mail => 'sent_at',
            default => 'created_at',
        };
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return match ($this) {
            self::Cases => ['Reference', 'Status', 'Source', 'Type', 'Title', 'Incident date', 'State', 'LGA', 'Community', 'Reporter', 'Phone', 'Email', 'Recorded'],
            self::Donations => ['Reference', 'Name', 'Email', 'Phone', 'Amount', 'Currency', 'Cause', 'Frequency', 'Payment', 'Anonymous', 'Source', 'Recorded'],
            self::Members => ['Member number', 'Name', 'Gender', 'Phone', 'Email', 'City', 'State', 'Status', 'Joined', 'Recorded'],
            self::Attendance => ['Meeting date', 'Member number', 'Member name', 'Marked by', 'Marked at'],
            self::Contacts => ['Reference', 'Status', 'Source', 'Name', 'Email', 'Phone', 'Subject', 'Recorded'],
            self::Volunteers => ['Reference', 'Status', 'Source', 'Name', 'Email', 'Phone', 'City', 'Interest', 'Recorded'],
            self::Subscribers => ['Reference', 'Status', 'Email', 'Country', 'City', 'Source', 'Recorded'],
            self::Mail => ['Sent at', 'Template', 'Audience', 'Recipient', 'Subject', 'Recipients', 'Sent by'],
        };
    }
}
