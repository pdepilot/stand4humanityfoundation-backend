<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\EventAction;
use App\Models\FoundationEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.events.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_are_forbidden_from_the_events_desk(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.events.index'))
            ->assertForbidden();
    }

    public function test_administrator_sees_the_empty_calendar(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.events.index'))
            ->assertOk()
            ->assertSee('Gatherings')
            ->assertSee('No gatherings on the desk yet.')
            ->assertSee('Schedule a gathering');
    }

    public function test_administrator_sees_gatherings_on_the_ledger(): void
    {
        $administrator = User::factory()->administrator()->create();
        FoundationEvent::factory()->create([
            'title' => 'Community Awareness Open Day',
            'slug' => 'community-awareness-open-day',
        ]);
        FoundationEvent::factory()->draft()->create([
            'title' => 'Draft town hall',
            'slug' => 'draft-town-hall',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.events.index'))
            ->assertOk()
            ->assertSee('Community Awareness Open Day')
            ->assertSee('Draft town hall')
            ->assertSee('Live')
            ->assertSee('Draft')
            ->assertSee('2 events');
    }

    public function test_calendar_ledger_escapes_dangerous_content(): void
    {
        $administrator = User::factory()->administrator()->create();
        FoundationEvent::factory()->create([
            'title' => "<script>alert('xss')</script>",
            'slug' => 'safe-slug',
            'summary' => '<img src=x onerror=alert(1)>',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.events.index'))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_staff_cannot_schedule_a_gathering(): void
    {
        $disk = Storage::fake('public');
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->post(route('portal.events.store'), $this->validPayload())
            ->assertForbidden();

        $this->assertDatabaseCount('foundation_events', 0);
        $disk->assertDirectoryEmpty('events');
    }

    public function test_empty_payload_returns_validation_errors(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.events.create'))
            ->post(route('portal.events.store'), [])
            ->assertRedirect(route('portal.events.create'))
            ->assertSessionHasErrors([
                'title',
                'slug',
                'summary',
                'description',
                'image',
                'image_alt',
                'location',
                'action',
                'starts_at',
            ]);

        $this->assertDatabaseCount('foundation_events', 0);
    }

    public function test_administrator_can_publish_a_gathering_that_appears_on_the_public_page(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.events.store'), $this->validPayload())
            ->assertRedirectToRoute('portal.events.show', 'youth-safety-town-hall')
            ->assertSessionHas('status', 'The gathering is on the calendar.');

        $event = FoundationEvent::query()->first();

        $this->assertNotNull($event);
        $this->assertSame('Youth Safety Town Hall', $event->title);
        $this->assertSame(EventAction::Mission, $event->action);
        $this->assertTrue($event->is_published);
        $this->assertTrue($event->is_featured);
        $this->assertSame(['Street safety', 'Peer reporting'], $event->highlightList());
        $this->assertTrue($event->hasUploadedImage());
        $disk->assertExists($event->image);

        $this->get(route('events'))
            ->assertOk()
            ->assertSee('Youth Safety Town Hall')
            ->assertSee('Neighbours and young people meet to talk about safer streets')
            ->assertSee('Owerri, Imo State');
    }

    public function test_a_new_featured_gathering_replaces_the_previous_feature(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $current = FoundationEvent::factory()->featured()->create([
            'title' => 'Current feature',
            'slug' => 'current-feature',
        ]);

        $this->actingAs($administrator)
            ->post(route('portal.events.store'), $this->validPayload([
                'title' => 'New feature',
                'slug' => 'new-feature',
                'is_featured' => '1',
            ]))
            ->assertRedirect();

        $this->assertFalse($current->fresh()->is_featured);
        $this->assertTrue(FoundationEvent::query()->where('slug', 'new-feature')->first()?->is_featured);
        $disk->assertExists(FoundationEvent::query()->where('slug', 'new-feature')->first()?->image ?? '');
    }

    public function test_drafts_stay_off_the_public_page(): void
    {
        FoundationEvent::factory()->draft()->create([
            'title' => 'Private rehearsal',
            'slug' => 'private-rehearsal',
        ]);

        $this->get(route('events'))
            ->assertOk()
            ->assertDontSee('Private rehearsal')
            ->assertSee('The next gathering is being prepared');
    }

    public function test_administrator_can_update_a_gathering_and_the_public_page_follows(): void
    {
        $administrator = User::factory()->administrator()->create();
        $event = FoundationEvent::factory()->create([
            'title' => 'Old walk',
            'slug' => 'old-walk',
            'is_featured' => false,
        ]);

        $payload = $this->validPayload([
            'title' => 'Human Rights Awareness Walk',
            'slug' => 'human-rights-awareness-walk',
            'is_featured' => '0',
        ]);
        unset($payload['image']);

        $this->actingAs($administrator)
            ->put(route('portal.events.update', $event), $payload)
            ->assertRedirectToRoute('portal.events.show', 'human-rights-awareness-walk')
            ->assertSessionHas('status', 'The gathering was updated.');

        $this->assertSame('Human Rights Awareness Walk', $event->fresh()->title);

        $this->get(route('events'))
            ->assertOk()
            ->assertSee('Human Rights Awareness Walk');
    }

    public function test_staff_cannot_update_a_gathering(): void
    {
        $staff = User::factory()->create();
        $event = FoundationEvent::factory()->create([
            'title' => 'Protected gathering',
        ]);

        $payload = $this->validPayload([
            'title' => 'Hacked gathering',
        ]);
        unset($payload['image']);

        $this->actingAs($staff)
            ->put(route('portal.events.update', $event), $payload)
            ->assertForbidden();

        $this->assertSame('Protected gathering', $event->fresh()->title);
    }

    public function test_administrator_can_remove_a_gathering(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $path = UploadedFile::fake()->image('remove.jpg')->store('events', 'public');
        $event = FoundationEvent::factory()->create([
            'title' => 'Cancelled briefing',
            'image' => $path,
        ]);

        $this->actingAs($administrator)
            ->delete(route('portal.events.destroy', $event))
            ->assertRedirectToRoute('portal.events.index')
            ->assertSessionHas('status', 'The gathering was removed from the calendar.');

        $this->assertModelMissing($event);
        $disk->assertMissing($path);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Youth Safety Town Hall',
            'slug' => 'youth-safety-town-hall',
            'summary' => 'Open dialogue with young people on street safety and peer reporting.',
            'description' => 'Neighbours and young people meet to talk about safer streets and how to ask for help.',
            'image' => UploadedFile::fake()->image('town-hall.jpg'),
            'image_alt' => 'Youth safety forum in Owerri',
            'location' => 'Owerri, Imo State',
            'audience' => 'Open to the public',
            'highlights' => "Street safety\nPeer reporting",
            'action' => EventAction::Mission->value,
            'starts_at' => '2026-09-06T14:00',
            'ends_at' => '2026-09-06T17:00',
            'is_published' => '1',
            'is_featured' => '1',
        ], $overrides);
    }
}
