<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\MailAudience;
use App\Enums\MailTemplate;
use App\Mail\FoundationMessage;
use App\Models\NewsletterSubscriber;
use App\Models\OutboundMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailCenterControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_staff_cannot_open_the_email_center(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.mail.index'))
            ->assertForbidden();
    }

    public function test_the_mail_form_embeds_template_copy_without_a_script_expression(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.mail.index'))
            ->assertOk()
            ->assertSee('id="mail-templates"', false)
            ->assertSee('data-templates="', false)
            ->assertSee('\u0022programme-note\u0022', false)
            ->assertSee("getAttribute('data-templates')", false)
            ->assertSee('Sending…', false)
            ->assertDontSee('{!!', false);
    }

    public function test_administrator_can_mail_one_person_with_a_document(): void
    {
        Mail::fake();
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.mail.store'), [
                'template' => 'thank-you',
                'audience' => 'individual',
                'recipient_name' => 'Ada Obi',
                'recipient_email' => 'ada@example.com',
                'subject' => 'Thank you for standing with us',
                'body' => "Your care reached someone today.\n\nWe will keep the work careful.",
                'attachments' => [UploadedFile::fake()->create('programme-note.pdf', 20, 'application/pdf')],
            ])
            ->assertRedirectToRoute('portal.mail.index')
            ->assertSessionHas('status', 'The message was sent to 1 person.');

        Mail::assertSent(FoundationMessage::class, function (FoundationMessage $mail): bool {
            return $mail->hasTo('ada@example.com')
                && $mail->headline === 'Thank you for standing with us'
                && $mail->recipientName === 'Ada Obi'
                && $mail->attachments() !== [];
        });

        $stored = OutboundMessage::query()->first();

        $this->assertNotNull($stored);
        $this->assertSame(['programme-note.pdf'], $stored->attachment_names);
        $this->assertSame(1, $stored->recipient_count);
    }

    public function test_administrator_can_mail_active_subscribers_only(): void
    {
        Mail::fake();
        $administrator = User::factory()->administrator()->create();
        NewsletterSubscriber::factory()->create(['email' => 'active@example.com']);
        NewsletterSubscriber::factory()->unsubscribed()->create([
            'email' => 'left@example.com',
        ]);

        $this->actingAs($administrator)
            ->post(route('portal.mail.store'), [
                'template' => 'programme-note',
                'audience' => 'subscribers',
                'subject' => 'A note from the desk',
                'body' => 'A short update from Owerri.',
            ])
            ->assertSessionHas('status', 'The message was sent to 1 person.');

        Mail::assertSent(FoundationMessage::class, fn (FoundationMessage $mail): bool => $mail->hasTo('active@example.com'));
        Mail::assertNotSent(FoundationMessage::class, fn (FoundationMessage $mail): bool => $mail->hasTo('left@example.com'));
    }

    public function test_an_empty_group_is_not_sent(): void
    {
        Mail::fake();
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.mail.index'))
            ->post(route('portal.mail.store'), [
                'template' => 'event-invitation',
                'audience' => 'donors',
                'subject' => 'Join us',
                'body' => 'Come as you are.',
            ])
            ->assertRedirect(route('portal.mail.index'))
            ->assertSessionHasErrors(['audience' => 'There is no one to receive this message.']);

        Mail::assertNothingSent();
        $this->assertDatabaseCount('outbound_messages', 0);
    }

    public function test_the_branded_message_includes_the_logo_and_footer(): void
    {
        $mail = new FoundationMessage(
            headline: 'Thank you',
            paragraphs: ['Your care reached someone today.'],
            template: MailTemplate::ThankYou,
            audience: MailAudience::Individual,
            recipientName: 'Ada Obi',
        );

        $mail->assertSeeInHtml('Stand For Humanity Foundation');
        $mail->assertSeeInHtml('Your care reached someone today.');
        $mail->assertSeeInHtml('Hello Ada Obi');
        $mail->assertSeeInHtml('info@standforhumanityfoundation.org');
        $mail->assertSeeInHtml(route('privacy'));
        $mail->assertSeeInText('With care,');
    }
}
