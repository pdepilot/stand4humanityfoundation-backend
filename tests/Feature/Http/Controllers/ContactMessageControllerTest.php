<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\ContactSource;
use App\Enums\ContactStatus;
use App\Mail\ContactReceived;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContactMessageControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_contact_page_includes_csrf_token_and_capture_script(): void
    {
        $this->get(route('contact'))
            ->assertSee('name="csrf-token"', false)
            ->assertSee('Charitize/js/contact-capture.js', false)
            ->assertSee('id="contactCaptureForm"', false)
            ->assertSee('/api/contacts', false);
    }

    public function test_empty_payload_returns_422_with_required_field_messages(): void
    {
        $this->postJson(route('contacts.api.store'), [])
            ->assertUnprocessable()
            ->assertJsonPath('errors.name.0', 'Enter your name.')
            ->assertJsonPath('errors.email.0', 'Enter your email address.')
            ->assertJsonPath('errors.subject.0', 'Enter the type of assistance you need.')
            ->assertJsonPath('errors.message.0', 'Describe your request.');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_returns_422_when_the_email_is_invalid(): void
    {
        $this->postJson(route('contacts.api.store'), $this->validPayload([
            'email' => 'not-an-email',
        ]))
            ->assertUnprocessable()
            ->assertJsonPath('errors.email.0', 'Enter a valid email address.');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_returns_422_when_the_message_is_too_short(): void
    {
        $this->postJson(route('contacts.api.store'), $this->validPayload([
            'message' => 'Too short',
        ]))
            ->assertUnprocessable()
            ->assertJsonPath('errors.message.0', 'Describe your request in at least 10 characters.');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_honeypot_payload_is_rejected_without_saving(): void
    {
        $this->postJson(route('contacts.api.store'), $this->validPayload([
            'website' => 'https://spam.test',
        ]))
            ->assertUnprocessable()
            ->assertJsonPath('errors.website.0', 'Unable to accept this message.');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_valid_payload_creates_a_public_message_and_returns_201(): void
    {
        $response = $this->postJson(route('contacts.api.store'), $this->validPayload());

        $response
            ->assertCreated()
            ->assertJsonPath('ok', true)
            ->assertJsonMissingPath('contact.name')
            ->assertJsonMissingPath('contact.email')
            ->assertJsonMissingPath('contact.message')
            ->assertJsonMissingPath('contact.source')
            ->assertDontSee('Ada Obi')
            ->assertDontSee('ada@example.com');

        $message = ContactMessage::query()->first();

        $this->assertNotNull($message);
        $this->assertTrue(Str::startsWith($message->reference, 'cnt-'));
        $this->assertSame($message->reference, $response->json('contact.reference'));
        $this->assertSame(ContactSource::Public, $message->source);
        $this->assertSame(ContactStatus::New, $message->status);
        $this->assertSame('Ada Obi', $message->name);
        $this->assertSame('ada@example.com', $message->email);
        $this->assertSame('Child protection help', $message->subject);
        $this->assertSame('We need guidance on how to request assistance for a family in Owerri.', $message->message);
    }

    public function test_a_public_contact_sends_a_receipt_with_the_reference(): void
    {
        Mail::fake();

        $this->postJson(route('contacts.api.store'), $this->validPayload())
            ->assertCreated();

        $message = ContactMessage::query()->first();

        $this->assertNotNull($message);

        Mail::assertSent(ContactReceived::class, function (ContactReceived $mail) use ($message): bool {
            return $mail->hasTo('ada@example.com')
                && $mail->contactMessage->is($message)
                && str_contains($mail->envelope()->subject, $message->reference);
        });
    }

    public function test_unexpected_status_reference_and_source_keys_are_ignored(): void
    {
        $response = $this->postJson(route('contacts.api.store'), $this->validPayload([
            'status' => 'closed',
            'reference' => 'cnt-hacked00001',
            'source' => 'desk',
        ]));

        $response->assertCreated();

        $message = ContactMessage::query()->first();

        $this->assertNotNull($message);
        $this->assertSame(ContactStatus::New, $message->status);
        $this->assertSame(ContactSource::Public, $message->source);
        $this->assertNotSame('cnt-hacked00001', $message->reference);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'subject' => 'Child protection help',
            'message' => 'We need guidance on how to request assistance for a family in Owerri.',
        ], $overrides);
    }
}
