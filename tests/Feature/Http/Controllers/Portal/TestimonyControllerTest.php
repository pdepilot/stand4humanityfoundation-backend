<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\TestimonyStatus;
use App\Models\Testimony;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TestimonyControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.testimonies.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_cannot_review_testimonies(): void
    {
        $staff = User::factory()->create();
        $testimony = Testimony::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.testimonies.index'))
            ->assertForbidden();

        $this->actingAs($staff)
            ->put(route('portal.testimonies.update', $testimony), [
                'status' => TestimonyStatus::Approved->value,
            ])
            ->assertForbidden();

        $this->assertSame(TestimonyStatus::Pending, $testimony->refresh()->status);
    }

    public function test_administrator_can_approve_a_testimony(): void
    {
        $administrator = User::factory()->administrator()->create();
        $testimony = Testimony::factory()->create([
            'name' => 'Ada Obi',
            'body' => 'The foundation stood with our family when we had nowhere else to turn.',
        ]);

        $this->actingAs($administrator)
            ->put(route('portal.testimonies.update', $testimony), [
                'status' => TestimonyStatus::Approved->value,
            ])
            ->assertRedirect(route('portal.testimonies.show', $testimony))
            ->assertSessionHas('status', 'The testimony was approved and is on the homepage.');

        $testimony->refresh();

        $this->assertSame(TestimonyStatus::Approved, $testimony->status);
        $this->assertSame($administrator->id, $testimony->reviewed_by);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('The foundation stood with our family when we had nowhere else to turn.');
    }

    public function test_administrator_can_decline_a_published_testimony(): void
    {
        $administrator = User::factory()->administrator()->create();
        $testimony = Testimony::factory()->approved()->create([
            'body' => 'The foundation stood with our family when we had nowhere else to turn.',
        ]);

        $this->actingAs($administrator)
            ->put(route('portal.testimonies.update', $testimony), [
                'status' => TestimonyStatus::Declined->value,
            ])
            ->assertRedirect(route('portal.testimonies.show', $testimony))
            ->assertSessionHas('status', 'The testimony was declined.');

        $this->assertSame(TestimonyStatus::Declined, $testimony->refresh()->status);

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('The foundation stood with our family when we had nowhere else to turn.');
    }

    public function test_administrator_can_remove_a_testimony(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $testimony = Testimony::factory()->approved()->create([
            'photo' => 'testimonies/ada.jpg',
        ]);
        $disk->put('testimonies/ada.jpg', 'portrait');

        $this->actingAs($administrator)
            ->delete(route('portal.testimonies.destroy', $testimony))
            ->assertRedirect(route('portal.testimonies.index'))
            ->assertSessionHas('status', 'The testimony was removed.');

        $this->assertModelMissing($testimony);
        $disk->assertMissing('testimonies/ada.jpg');
    }

    public function test_administrator_can_publish_a_testimony_from_the_desk(): void
    {
        Storage::fake('public');
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.testimonies.store'), [
                'name' => 'Ada Obi',
                'role' => 'Parent',
                'body' => 'The foundation stood with our family when we had nowhere else to turn.',
                'rating' => 5,
                'photo' => UploadedFile::fake()->image('ada.jpg'),
            ])
            ->assertRedirect();

        $testimony = Testimony::query()->first();

        $this->assertNotNull($testimony);
        $this->assertSame(TestimonyStatus::Approved, $testimony->status);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Ada Obi');
    }
}
