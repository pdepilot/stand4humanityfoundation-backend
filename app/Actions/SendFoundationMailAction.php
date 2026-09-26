<?php

namespace App\Actions;

use App\Enums\MailAudience;
use App\Enums\NewsletterStatus;
use App\Http\Requests\Portal\SendFoundationMailRequest;
use App\Mail\FoundationMessage;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\NewsletterSubscriber;
use App\Models\OutboundMessage;
use App\Models\User;
use App\Models\VolunteerApplication;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendFoundationMailAction
{
    /**
     * Send a branded message to one person or a foundation group.
     */
    public function send(SendFoundationMailRequest $request, User $sender): OutboundMessage
    {
        $audience = $request->audience();
        $recipients = $this->recipients($request);

        if ($recipients === []) {
            throw new \InvalidArgumentException('There is no one to receive this message.');
        }
        $documents = [];

        foreach ($request->documents() as $file) {
            $documents[] = [
                'name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType() ?: 'application/octet-stream',
                'contents' => (string) $file->get(),
            ];
        }

        $paragraphs = $this->paragraphs((string) $request->validated('body'));
        $subject = (string) $request->validated('subject');
        $template = $request->template();

        $message = OutboundMessage::query()->create([
            'sent_by' => $sender->id,
            'template' => $template,
            'audience' => $audience,
            'recipient_email' => $audience === MailAudience::Individual ? $recipients[0]['email'] : null,
            'subject' => $subject,
            'body' => (string) $request->validated('body'),
            'attachment_names' => array_map(
                fn (array $document): string => $document['name'],
                $documents,
            ),
            'recipient_count' => count($recipients),
            'sent_at' => now(),
        ]);

        dispatch(function () use ($recipients, $documents, $paragraphs, $subject, $template, $audience): void {
            $attachments = array_map(
                fn (array $document): Attachment => Attachment::fromData(
                    fn (): string => $document['contents'],
                    $document['name'],
                )->withMime($document['mime']),
                $documents,
            );

            foreach ($recipients as $recipient) {
                try {
                    Mail::to($recipient['email'], $recipient['name'])->send(new FoundationMessage(
                        headline: $subject,
                        paragraphs: $paragraphs,
                        template: $template,
                        audience: $audience,
                        recipientName: $recipient['name'],
                        documents: $attachments,
                    ));
                } catch (Throwable $exception) {
                    report($exception);
                }
            }
        })->afterResponse();

        return $message;
    }

    /**
     * @return list<array{name: string, email: string}>
     */
    private function recipients(SendFoundationMailRequest $request): array
    {
        $audience = $request->audience();

        if ($audience === MailAudience::Individual) {
            return [[
                'name' => $request->filled('recipient_name') ? (string) $request->validated('recipient_name') : 'Friend',
                'email' => (string) $request->validated('recipient_email'),
            ]];
        }

        $rows = match ($audience) {
            MailAudience::Subscribers => NewsletterSubscriber::query()
                ->where('status', NewsletterStatus::Active)
                ->get(['email']),
            MailAudience::Donors => Donation::query()->whereNotNull('email')->get(['name', 'email']),
            MailAudience::Volunteers => VolunteerApplication::query()->whereNotNull('email')->get(['name', 'email']),
            MailAudience::Contacts => ContactMessage::query()->whereNotNull('email')->get(['name', 'email']),
            MailAudience::Individual => collect(),
        };

        return $rows
            ->map(fn ($row): array => [
                'name' => isset($row->name) && is_string($row->name) && $row->name !== '' ? $row->name : 'Friend',
                'email' => strtolower((string) $row->email),
            ])
            ->filter(fn (array $recipient): bool => $recipient['email'] !== '')
            ->unique('email')
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    private function paragraphs(string $body): array
    {
        return collect(preg_split("/\r\n|\n|\r/", $body) ?: [])
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
