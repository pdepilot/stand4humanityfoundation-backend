<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\EventAction;
use App\Models\FoundationEvent;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_published_gatherings_fill_the_featured_slot_and_the_card_grid(): void
    {
        FoundationEvent::factory()->featured()->create([
            'title' => 'Community Awareness Open Day',
            'slug' => 'community-awareness-open-day',
            'description' => 'A public gathering for families and volunteers.',
            'audience' => 'Open to the public',
            'highlights' => ['Child protection information'],
            'starts_at' => '2026-08-18 09:00:00',
            'ends_at' => '2026-08-18 16:00:00',
        ]);
        FoundationEvent::factory()->create([
            'title' => 'Human Rights Awareness Walk',
            'slug' => 'human-rights-awareness-walk',
            'summary' => 'A peaceful city walk across Imo State.',
            'action' => EventAction::Contact,
            'starts_at' => '2026-08-02 07:00:00',
            'ends_at' => '2026-08-02 11:00:00',
        ]);
        FoundationEvent::factory()->draft()->create([
            'title' => 'Unpublished clinic',
            'slug' => 'unpublished-clinic',
        ]);

        $this->get(route('events'))
            ->assertOk()
            ->assertSee('Events | Stand For Humanity Foundation', false)
            ->assertSee('Community Awareness Open Day')
            ->assertSee('A public gathering for families and volunteers.')
            ->assertSee('Child protection information')
            ->assertSee('Human Rights Awareness Walk')
            ->assertSee('A peaceful city walk across Imo State.')
            ->assertSee(route('contact'), false)
            ->assertDontSee('Unpublished clinic');
    }
}
