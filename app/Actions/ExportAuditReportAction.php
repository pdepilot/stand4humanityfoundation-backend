<?php

namespace App\Actions;

use App\Enums\AuditReport;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\FoundationMember;
use App\Models\IncidentReport;
use App\Models\MeetingAttendance;
use App\Models\NewsletterSubscriber;
use App\Models\OutboundMessage;
use App\Models\VolunteerApplication;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportAuditReportAction
{
    /**
     * Stream a CSV of the chosen ledger for the optional date range.
     */
    public function download(AuditReport $report, ?string $from, ?string $to): StreamedResponse
    {
        $filename = 'audit-'.$report->value.'-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($report, $from, $to): void {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fputcsv($handle, $report->headings());

            foreach ($this->query($report, $from, $to)->lazy(200) as $record) {
                fputcsv($handle, array_map($this->cell(...), $this->columns($report, $record)));
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * @return Builder<Model>
     */
    private function query(AuditReport $report, ?string $from, ?string $to): Builder
    {
        $query = match ($report) {
            AuditReport::Cases => IncidentReport::query()->orderBy('id'),
            AuditReport::Donations => Donation::query()->orderBy('id'),
            AuditReport::Members => FoundationMember::query()->orderBy('id'),
            AuditReport::Attendance => MeetingAttendance::query()->with(['member', 'marker'])->orderBy('id'),
            AuditReport::Contacts => ContactMessage::query()->orderBy('id'),
            AuditReport::Volunteers => VolunteerApplication::query()->orderBy('id'),
            AuditReport::Subscribers => NewsletterSubscriber::query()->orderBy('id'),
            AuditReport::Mail => OutboundMessage::query()->with('sender')->orderBy('id'),
        };

        return $this->limitDates($query, $report->dateColumn(), $from, $to);
    }

    /**
     * @param  Builder<Model>  $query
     * @return Builder<Model>
     */
    private function limitDates(Builder $query, string $column, ?string $from, ?string $to): Builder
    {
        if (is_string($from) && $from !== '') {
            $query->whereDate($column, '>=', $from);
        }

        if (is_string($to) && $to !== '') {
            $query->whereDate($column, '<=', $to);
        }

        return $query;
    }

    /**
     * @return list<mixed>
     */
    private function columns(AuditReport $report, Model $record): array
    {
        return match ($report) {
            AuditReport::Cases => $this->caseColumns($record),
            AuditReport::Donations => $this->donationColumns($record),
            AuditReport::Members => $this->memberColumns($record),
            AuditReport::Attendance => $this->attendanceColumns($record),
            AuditReport::Contacts => $this->contactColumns($record),
            AuditReport::Volunteers => $this->volunteerColumns($record),
            AuditReport::Subscribers => $this->subscriberColumns($record),
            AuditReport::Mail => $this->mailColumns($record),
        };
    }

    /**
     * @return list<mixed>
     */
    private function caseColumns(Model $record): array
    {
        /** @var IncidentReport $record */
        return [
            $record->reference,
            $record->status->label(),
            $record->source->label(),
            $record->incident_type->label(),
            $record->incident_title,
            $this->date($record->incident_date),
            $record->state,
            $record->lga,
            $record->community,
            $record->full_name,
            $record->phone,
            $record->email,
            $this->dateTime($record->created_at),
        ];
    }

    /**
     * @return list<mixed>
     */
    private function donationColumns(Model $record): array
    {
        /** @var Donation $record */
        return [
            $record->reference,
            $record->name,
            $record->email,
            $record->phone,
            $record->amount,
            $record->currency->label(),
            $record->cause->label(),
            $record->frequency->label(),
            $record->payment_method->label(),
            $record->anonymous ? 'Yes' : 'No',
            $record->source->label(),
            $this->dateTime($record->created_at),
        ];
    }

    /**
     * @return list<mixed>
     */
    private function memberColumns(Model $record): array
    {
        /** @var FoundationMember $record */
        return [
            $record->member_number,
            $record->full_name,
            $record->gender->label(),
            $record->phone,
            $record->email,
            $record->city,
            $record->state,
            $record->status->label(),
            $this->date($record->joined_on),
            $this->dateTime($record->created_at),
        ];
    }

    /**
     * @return list<mixed>
     */
    private function attendanceColumns(Model $record): array
    {
        /** @var MeetingAttendance $record */
        return [
            $this->date($record->meeting_on),
            $record->member?->member_number,
            $record->member?->full_name,
            $record->marker?->name,
            $this->dateTime($record->marked_at),
        ];
    }

    /**
     * @return list<mixed>
     */
    private function contactColumns(Model $record): array
    {
        /** @var ContactMessage $record */
        return [
            $record->reference,
            $record->status->label(),
            $record->source->label(),
            $record->name,
            $record->email,
            $record->phone,
            $record->subject,
            $this->dateTime($record->created_at),
        ];
    }

    /**
     * @return list<mixed>
     */
    private function volunteerColumns(Model $record): array
    {
        /** @var VolunteerApplication $record */
        return [
            $record->reference,
            $record->status->label(),
            $record->source->label(),
            $record->name,
            $record->email,
            $record->phone,
            $record->city,
            $record->interest->label(),
            $this->dateTime($record->created_at),
        ];
    }

    /**
     * @return list<mixed>
     */
    private function subscriberColumns(Model $record): array
    {
        /** @var NewsletterSubscriber $record */
        return [
            $record->reference,
            $record->status->label(),
            $record->email,
            $record->country,
            $record->city,
            $record->source->label(),
            $this->dateTime($record->created_at),
        ];
    }

    /**
     * @return list<mixed>
     */
    private function mailColumns(Model $record): array
    {
        /** @var OutboundMessage $record */
        return [
            $this->dateTime($record->sent_at),
            $record->template->label(),
            $record->audience->label(),
            $record->recipient_email,
            $record->subject,
            $record->recipient_count,
            $record->sender?->name,
        ];
    }

    private function cell(mixed $value): string
    {
        $text = match (true) {
            $value instanceof BackedEnum => (string) $value->value,
            $value instanceof Carbon => $value->toDateTimeString(),
            $value === null => '',
            is_bool($value) => $value ? 'Yes' : 'No',
            default => (string) $value,
        };

        if (preg_match('/^[=+\-@]/', $text) === 1) {
            return "'".$text;
        }

        return $text;
    }

    private function date(mixed $value): string
    {
        return $value instanceof Carbon ? $value->toDateString() : '';
    }

    private function dateTime(mixed $value): string
    {
        return $value instanceof Carbon ? $value->format('Y-m-d H:i') : '';
    }
}
