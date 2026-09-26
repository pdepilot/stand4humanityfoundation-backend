<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Models\User;
use App\Notifications\FrontendActivityNotification;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_public_frontend_activity_notifies_administrators_only(): void
    {
        Notification::fake();

        $administrator = User::factory()->administrator()->create();
        $staff = User::factory()->create();

        $this->postJson(route('contacts.api.store'), [
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'subject' => 'Child protection help',
            'message' => 'We need guidance on how to request assistance for a family in Owerri.',
        ])->assertCreated();

        Notification::assertSentTo($administrator, FrontendActivityNotification::class);
        Notification::assertNotSentTo($staff, FrontendActivityNotification::class);
    }

    public function test_public_donation_and_report_notify_the_administrator(): void
    {
        Notification::fake();

        $administrator = User::factory()->administrator()->create();

        $this->postJson(route('donations.api.store'), [
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'phone' => '08012345678',
            'amount' => 5000,
            'currency' => 'NGN',
            'cause' => 'child-protection',
            'frequency' => 'one-time',
            'paymentMethod' => 'paystack',
        ])->assertCreated();

        $this->postJson(route('reports.api.store'), [
            'full_name' => 'Ada Obi',
            'phone' => '08012345678',
            'relationship' => 'Witness',
            'incident_type' => 'Child Abuse',
            'incident_title' => 'Child in unsafe housing',
            'incident_date' => '2026-09-20',
            'state' => 'Imo',
            'description' => 'A child was seen in unsafe conditions and needs urgent protection from the foundation response team.',
            'confirm_accurate' => '1',
            'confirm_false' => '1',
            'consent_processing' => '1',
        ])->assertCreated();

        Notification::assertSentToTimes($administrator, FrontendActivityNotification::class, 2);
    }

    public function test_desk_entries_do_not_notify_administrators(): void
    {
        Notification::fake();

        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.contacts.store'), [
                'name' => 'Ngozi Eze',
                'email' => 'ngozi@example.com',
                'subject' => 'Volunteer inquiry',
                'message' => 'A neighbour asked the desk to record this request for follow-up.',
            ])
            ->assertRedirect();

        Notification::assertNothingSent();
    }

    public function test_guests_are_redirected_from_the_feed(): void
    {
        $this->getJson(route('portal.notifications.index'))
            ->assertUnauthorized();
    }

    public function test_staff_are_forbidden_from_the_feed(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->getJson(route('portal.notifications.index'))
            ->assertForbidden();
    }

    public function test_administrator_receives_the_feed_and_can_mark_alerts_read(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->postJson(route('contacts.api.store'), [
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'subject' => 'Child protection help',
            'message' => 'We need guidance on how to request assistance for a family in Owerri.',
        ])->assertCreated();

        $feed = $this->actingAs($administrator)
            ->getJson(route('portal.notifications.index'))
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('unread', 1)
            ->assertJsonPath('notifications.0.type', 'contact')
            ->assertJsonPath('notifications.0.title', 'Contact request received')
            ->assertJsonPath('notifications.0.body', 'Ada Obi · Child protection help');

        $id = $feed->json('notifications.0.id');
        $this->assertNotEmpty($id);

        $this->actingAs($administrator)
            ->postJson(route('portal.notifications.read', $id))
            ->assertOk()
            ->assertJsonPath('unread', 0);

        $this->assertNotNull($administrator->notifications()->first()?->read_at);
    }

    public function test_administrator_can_mark_every_alert_read(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->postJson(route('contacts.api.store'), [
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'subject' => 'Child protection help',
            'message' => 'We need guidance on how to request assistance for a family in Owerri.',
        ])->assertCreated();

        $this->actingAs($administrator)
            ->postJson(route('portal.notifications.read-all'))
            ->assertOk()
            ->assertJsonPath('unread', 0);

        $this->assertSame(0, $administrator->unreadNotifications()->count());
    }

    public function test_staff_cannot_mark_alerts_read(): void
    {
        $administrator = User::factory()->administrator()->create();
        $staff = User::factory()->create();

        $this->postJson(route('contacts.api.store'), [
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'subject' => 'Child protection help',
            'message' => 'We need guidance on how to request assistance for a family in Owerri.',
        ])->assertCreated();

        $id = $administrator->notifications()->first()?->id;

        $this->actingAs($staff)
            ->postJson(route('portal.notifications.read', $id))
            ->assertForbidden();
    }

    public function test_administrator_dashboard_includes_the_alert_bell(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.dashboard'))
            ->assertOk()
            ->assertSee('data-desk-bell', false)
            ->assertSee('js/portal/notifications.js', false);
    }

    public function test_staff_dashboard_does_not_include_the_alert_bell(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.dashboard'))
            ->assertOk()
            ->assertDontSee('data-desk-bell', false);
    }
}
