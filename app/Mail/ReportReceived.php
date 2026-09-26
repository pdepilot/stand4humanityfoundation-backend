<?php

namespace App\Mail;

use App\Models\IncidentReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public IncidentReport $report) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We received your report '.$this->report->reference,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'mail.report-received',
            text: 'mail.report-received-text',
            with: [
                'greetingName' => $this->greetingName(),
                'trackingUrl' => $this->trackingUrl(),
            ],
        );
    }

    public function greetingName(): string
    {
        $name = $this->report->full_name;

        return is_string($name) && trim($name) !== '' ? trim($name) : 'there';
    }

    public function trackingUrl(): string
    {
        return route('report').'#track-report';
    }
}
