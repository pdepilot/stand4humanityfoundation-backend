<?php

namespace App\Actions;

use App\Enums\ContactSource;
use App\Enums\ContactStatus;
use App\Events\FrontendActivityRecorded;
use App\Mail\ContactReceived;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class CreateContactMessageAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data, ContactSource $source = ContactSource::Public): ContactMessage
    {
        $message = ContactMessage::query()->create([
            'reference' => 'cnt-'.Str::lower(bin2hex(random_bytes(6))),
            'status' => ContactStatus::New,
            'source' => $source,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        if ($source === ContactSource::Public) {
            FrontendActivityRecorded::dispatch(
                'contact',
                'Contact request received',
                $message->name.' · '.$message->subject,
                route('portal.contacts.show', $message, false),
                $message->reference,
            );

            $this->sendReceipt($message);
        }

        return $message;
    }

    /**
     * Confirm to the sender that the desk has their message and reference.
     */
    private function sendReceipt(ContactMessage $message): void
    {
        if (! is_string($message->email) || trim($message->email) === '') {
            return;
        }

        try {
            Mail::to($message->email)->send(new ContactReceived($message));
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
