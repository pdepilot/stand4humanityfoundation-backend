<?php

namespace App\Mail;

use App\Models\FoundationMember;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberSuspended extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public FoundationMember $member, public bool $forAbsence = true) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your membership has been suspended',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'mail.member-suspended',
            text: 'mail.member-suspended-text',
        );
    }
}
