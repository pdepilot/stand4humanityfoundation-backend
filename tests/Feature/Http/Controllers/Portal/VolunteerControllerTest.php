<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\VolunteerInterest;
use App\Enums\VolunteerSource;
use App\Enums\VolunteerStatus;
use App\Models\User;
use App\Models\VolunteerApplication;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VolunteerControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.volunteers.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_are_forbidden_from_the_volunteer_desk(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.volunteers.index'))
            ->assertForbidden();
    }

    public function test_administrator_sees_the_empty_volunteer_ledger(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.volunteers.index'))
            ->assertOk()
            ->assertSee('Volunteer ledger')
            ->assertSee('No volunteer applications yet.')
            ->assertSee('Record an applicant');
    }

    public function test_administrator_sees_public_and_desk_applications(): void
    {
        $administrator = User::factory()->administrator()->create();
        VolunteerApplication::factory()->create([
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
        ]);
        VolunteerApplication::factory()->fromDesk()->create([
            'name' => 'Desk Officer',
            'email' => 'desk@example.com',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.volunteers.index'))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('Desk Officer')
            ->assertSee('Volunteer page')
            ->assertSee('Command desk')
            ->assertSee('2 applications');
    }

    public function test_administrator_can_record_a_desk_application(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.volunteers.store'), $this->validPayload())
            ->assertRedirectToRoute('portal.volunteers.show', VolunteerApplication::query()->first())
            ->assertSessionHas('status', 'The volunteer application is on the desk.');

        $application = VolunteerApplication::query()->first();

        $this->assertNotNull($application);
        $this->assertSame(VolunteerSource::Desk, $application->source);
        $this->assertSame(VolunteerStatus::Pending, $application->status);
        $this->assertSame('Ngozi Eze', $application->name);
        $this->assertSame('ngozi@example.com', $application->email);
        $this->assertSame('08098765432', $application->phone);
        $disk->assertExists($application->photo);
    }

    public function test_administrator_can_view_photo_email_and_phone_on_the_record(): void
    {
        Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $path = UploadedFile::fake()->image('desk.jpg')->store('volunteers', 'public');
        $application = VolunteerApplication::factory()->create([
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'phone' => '08012345678',
            'photo' => $path,
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.volunteers.show', $application))
            ->assertOk()
            ->assertSee('ada@example.com')
            ->assertSee('08012345678')
            ->assertSee($application->photoUrl(), false);
    }

    public function test_administrator_can_approve_an_application(): void
    {
        $administrator = User::factory()->administrator()->create();
        $application = VolunteerApplication::factory()->create([
            'status' => VolunteerStatus::Pending,
        ]);

        $this->actingAs($administrator)
            ->put(route('portal.volunteers.update', $application), [
                'status' => VolunteerStatus::Approved->value,
                'admin_notes' => 'Interview completed. Conduct standards understood.',
            ])
            ->assertRedirectToRoute('portal.volunteers.show', $application)
            ->assertSessionHas('status', 'The vetting status was updated.');

        $application->refresh();

        $this->assertSame(VolunteerStatus::Approved, $application->status);
        $this->assertSame($administrator->id, $application->reviewed_by);
        $this->assertNotNull($application->reviewed_at);
        $this->assertSame('Interview completed. Conduct standards understood.', $application->admin_notes);
    }

    public function test_administrator_can_decline_an_application(): void
    {
        $administrator = User::factory()->administrator()->create();
        $application = VolunteerApplication::factory()->create();

        $this->actingAs($administrator)
            ->put(route('portal.volunteers.update', $application), [
                'status' => VolunteerStatus::Declined->value,
                'admin_notes' => 'Unable to keep the required schedule.',
            ])
            ->assertRedirect();

        $this->assertSame(VolunteerStatus::Declined, $application->fresh()->status);
    }

    public function test_staff_cannot_update_vetting_status(): void
    {
        $staff = User::factory()->create();
        $application = VolunteerApplication::factory()->create();

        $this->actingAs($staff)
            ->put(route('portal.volunteers.update', $application), [
                'status' => VolunteerStatus::Approved->value,
            ])
            ->assertForbidden();

        $this->assertSame(VolunteerStatus::Pending, $application->fresh()->status);
    }

    public function test_administrator_can_remove_an_application(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $path = UploadedFile::fake()->image('remove.jpg')->store('volunteers', 'public');
        $application = VolunteerApplication::factory()->create([
            'photo' => $path,
        ]);

        $this->actingAs($administrator)
            ->delete(route('portal.volunteers.destroy', $application))
            ->assertRedirectToRoute('portal.volunteers.index');

        $this->assertModelMissing($application);
        $disk->assertMissing($path);
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
            'city' => 'Owerri',
            'interest' => VolunteerInterest::VictimSupport->value,
            'availability' => 'Weekday evenings',
            'experience' => 'Advocacy meetings with community groups.',
            'motivation' => 'I want to help people who cannot speak for themselves find safety and support.',
            'photo' => UploadedFile::fake()->image('ngozi.jpg', 600, 600),
        ], $overrides);
    }
}
