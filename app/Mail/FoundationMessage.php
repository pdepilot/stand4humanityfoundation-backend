<?php

namespace App\Mail;

use App\Enums\MailAudience;
use App\Enums\MailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FoundationMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  list<string>  $paragraphs
     * @param  list<Attachment>  $documents
     */
    public function __construct(
        public string $headline,
        public array $paragraphs,
        public MailTemplate $template,
        public MailAudience $audience,
        public ?string $recipientName,
        public array $documents = [],
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->headline,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'mail.foundation-message',
            text: 'mail.foundation-message-text',
        );
    }

    /**
     * @return list<Attachment>
     */
    public function attachments(): array
    {
        return $this->documents;
    }
}
