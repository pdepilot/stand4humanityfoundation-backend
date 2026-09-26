<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\NewsletterSource;
use App\Enums\NewsletterStatus;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class NewsletterSubscriberControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.subscribers.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_are_forbidden_from_the_subscriber_desk(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.subscribers.index'))
            ->assertForbidden();
    }

    public function test_administrator_sees_the_empty_subscriber_ledger(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.subscribers.index'))
            ->assertOk()
            ->assertSee('Subscriber ledger')
            ->assertSee('No newsletter subscribers yet.')
            ->assertSee('Add an email');
    }

    public function test_administrator_sees_public_and_desk_subscribers(): void
    {
        $administrator = User::factory()->administrator()->create();
        NewsletterSubscriber::factory()->create([
            'email' => 'public@example.com',
            'city' => 'Owerri',
            'country' => 'Nigeria',
        ]);
        NewsletterSubscriber::factory()->fromDesk()->create([
            'email' => 'desk@example.com',
            'city' => null,
            'country' => null,
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.subscribers.index'))
            ->assertOk()
            ->assertSee('public@example.com')
            ->assertSee('desk@example.com')
            ->assertSee('Owerri, Nigeria')
            ->assertSee('Public site')
            ->assertSee('Command desk')
            ->assertSee('2 subscribers');
    }

    public function test_administrator_can_add_a_desk_subscriber(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.subscribers.store'), [
                'email' => 'ngozi@example.com',
            ])
            ->assertRedirectToRoute('portal.subscribers.show', NewsletterSubscriber::query()->first())
            ->assertSessionHas('status', 'The subscriber is on the list.');

        $subscriber = NewsletterSubscriber::query()->first();

        $this->assertNotNull($subscriber);
        $this->assertSame('ngozi@example.com', $subscriber->email);
        $this->assertSame(NewsletterSource::Desk, $subscriber->source);
        $this->assertSame(NewsletterStatus::Active, $subscriber->status);
    }

    public function test_administrator_can_unsubscribe_a_subscriber(): void
    {
        $administrator = User::factory()->administrator()->create();
        $subscriber = NewsletterSubscriber::factory()->create();

        $this->actingAs($administrator)
            ->put(route('portal.subscribers.update', $subscriber), [
                'status' => NewsletterStatus::Unsubscribed->value,
            ])
            ->assertRedirectToRoute('portal.subscribers.show', $subscriber)
            ->assertSessionHas('status', 'The subscription status was updated.');

        $subscriber->refresh();

        $this->assertSame(NewsletterStatus::Unsubscribed, $subscriber->status);
        $this->assertNotNull($subscriber->unsubscribed_at);
    }

    public function test_staff_cannot_update_subscriber_status(): void
    {
        $staff = User::factory()->create();
        $subscriber = NewsletterSubscriber::factory()->create();

        $this->actingAs($staff)
            ->put(route('portal.subscribers.update', $subscriber), [
                'status' => NewsletterStatus::Unsubscribed->value,
            ])
            ->assertForbidden();

        $this->assertSame(NewsletterStatus::Active, $subscriber->fresh()->status);
    }

    public function test_administrator_can_remove_a_subscriber(): void
    {
        $administrator = User::factory()->administrator()->create();
        $subscriber = NewsletterSubscriber::factory()->create();

        $this->actingAs($administrator)
            ->delete(route('portal.subscribers.destroy', $subscriber))
            ->assertRedirectToRoute('portal.subscribers.index');

        $this->assertModelMissing($subscriber);
    }
}
