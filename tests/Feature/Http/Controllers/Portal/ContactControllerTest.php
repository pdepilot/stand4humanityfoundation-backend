<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\ContactSource;
use App\Enums\ContactStatus;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.contacts.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_are_forbidden_from_the_contact_desk(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.contacts.index'))
            ->assertForbidden();
    }

    public function test_administrator_sees_the_empty_contact_ledger(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.contacts.index'))
            ->assertOk()
            ->assertSee('Contact ledger')
            ->assertSee('No messages on the desk yet.')
            ->assertSee('Record a message');
    }

    public function test_administrator_sees_public_and_desk_messages_on_the_ledger(): void
    {
        $administrator = User::factory()->administrator()->create();
        ContactMessage::factory()->create([
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'subject' => 'Public request',
        ]);
        ContactMessage::factory()->fromDesk()->create([
            'name' => 'Desk Officer',
            'email' => 'desk@example.com',
            'subject' => 'Desk note',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.contacts.index'))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('ada@example.com')
            ->assertSee('Desk Officer')
            ->assertSee('Public contact page')
            ->assertSee('Command desk')
            ->assertSee('2 messages');
    }

    public function test_contact_ledger_escapes_dangerous_content(): void
    {
        $administrator = User::factory()->administrator()->create();
        ContactMessage::factory()->create([
            'name' => "<script>alert('xss')</script>",
            'email' => 'safe@example.com',
            'subject' => '<img src=x onerror=alert(1)>',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.contacts.index'))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_administrator_can_view_the_record_form(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.contacts.create'))
            ->assertOk()
            ->assertSee('Record a message')
            ->assertSee('Contact details')
            ->assertSee('Type of assistance');
    }

    public function test_staff_cannot_submit_a_desk_message(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->post(route('portal.contacts.store'), $this->validPayload())
            ->assertForbidden();

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_empty_desk_payload_returns_validation_errors(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.contacts.create'))
            ->post(route('portal.contacts.store'), [])
            ->assertRedirect(route('portal.contacts.create'))
            ->assertSessionHasErrors([
                'name' => 'Enter your name.',
                'email' => 'Enter your email address.',
                'subject' => 'Enter the type of assistance you need.',
                'message' => 'Describe your request.',
            ]);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_administrator_can_record_a_desk_message(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.contacts.create'))
            ->post(route('portal.contacts.store'), $this->validPayload())
            ->assertRedirectToRoute('portal.contacts.show', ContactMessage::query()->first())
            ->assertSessionHas('status', 'The message is on the desk.');

        $message = ContactMessage::query()->first();

        $this->assertNotNull($message);
        $this->assertSame(ContactSource::Desk, $message->source);
        $this->assertSame(ContactStatus::New, $message->status);
        $this->assertSame('Ngozi Eze', $message->name);
        $this->assertSame('ngozi@example.com', $message->email);
        $this->assertSame('08098765432', $message->phone);
    }

    public function test_desk_status_reference_and_source_keys_are_ignored(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.contacts.store'), $this->validPayload([
                'status' => 'closed',
                'reference' => 'cnt-hacked00001',
                'source' => 'public',
            ]))
            ->assertRedirect();

        $message = ContactMessage::query()->first();

        $this->assertNotNull($message);
        $this->assertSame(ContactSource::Desk, $message->source);
        $this->assertSame(ContactStatus::New, $message->status);
        $this->assertNotSame('cnt-hacked00001', $message->reference);
    }

    public function test_administrator_can_view_a_contact_record(): void
    {
        $administrator = User::factory()->administrator()->create();
        $message = ContactMessage::factory()->create([
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'phone' => '08012345678',
            'subject' => 'Victim support',
            'message' => 'We need guidance on how to request assistance for a family in Owerri.',
            'reference' => 'cnt-show00000001',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.contacts.show', $message))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('ada@example.com')
            ->assertSee('08012345678')
            ->assertSee('Victim support')
            ->assertSee('cnt-show00000001')
            ->assertSee('We need guidance on how to request assistance')
            ->assertSee('Update status')
            ->assertSee('Remove record');
    }

    public function test_contact_record_escapes_dangerous_content(): void
    {
        $administrator = User::factory()->administrator()->create();
        $message = ContactMessage::factory()->create([
            'name' => "<script>alert('xss')</script>",
            'subject' => '<img src=x onerror=alert(1)>',
            'message' => '<svg onload=alert(1)> We need guidance on how to request assistance for a family in Owerri.',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.contacts.show', $message))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false)
            ->assertDontSee('<svg onload=alert(1)>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_staff_cannot_view_a_contact_record(): void
    {
        $staff = User::factory()->create();
        $message = ContactMessage::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.contacts.show', $message))
            ->assertForbidden();
    }

    public function test_administrator_can_update_contact_status(): void
    {
        $administrator = User::factory()->administrator()->create();
        $message = ContactMessage::factory()->create([
            'status' => ContactStatus::New,
        ]);

        $this->actingAs($administrator)
            ->from(route('portal.contacts.show', $message))
            ->put(route('portal.contacts.update', $message), [
                'status' => ContactStatus::Replied->value,
            ])
            ->assertRedirectToRoute('portal.contacts.show', $message)
            ->assertSessionHas('status', 'The message status was updated.');

        $this->assertSame(ContactStatus::Replied, $message->fresh()->status);
    }

    public function test_staff_cannot_update_contact_status(): void
    {
        $staff = User::factory()->create();
        $message = ContactMessage::factory()->create();

        $this->actingAs($staff)
            ->put(route('portal.contacts.update', $message), [
                'status' => ContactStatus::Closed->value,
            ])
            ->assertForbidden();

        $this->assertSame(ContactStatus::New, $message->fresh()->status);
    }

    public function test_administrator_can_remove_a_contact_record(): void
    {
        $administrator = User::factory()->administrator()->create();
        $message = ContactMessage::factory()->create();

        $this->actingAs($administrator)
            ->from(route('portal.contacts.show', $message))
            ->delete(route('portal.contacts.destroy', $message))
            ->assertRedirectToRoute('portal.contacts.index')
            ->assertSessionHas('status', 'The message was removed from the desk.');

        $this->assertModelMissing($message);
    }

    public function test_staff_cannot_remove_a_contact_record(): void
    {
        $staff = User::factory()->create();
        $message = ContactMessage::factory()->create();

        $this->actingAs($staff)
            ->delete(route('portal.contacts.destroy', $message))
            ->assertForbidden();

        $this->assertModelExists($message);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Ngozi Eze',
            'email' => 'ngozi@example.com',
            'phone' => '08098765432',
            'subject' => 'Volunteer inquiry',
            'message' => 'A neighbour asked the desk to record this request for follow-up.',
        ], $overrides);
    }
}
