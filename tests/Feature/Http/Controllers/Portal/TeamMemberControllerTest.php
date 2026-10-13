<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeamMemberControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.team.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_cannot_manage_the_team(): void
    {
        $staff = User::factory()->create();
        $teamMember = TeamMember::factory()->create([
            'name' => 'Ada Obi',
            'role' => 'Programme lead',
        ]);

        $this->actingAs($staff)
            ->get(route('portal.team.index'))
            ->assertForbidden();

        $this->actingAs($staff)
            ->post(route('portal.team.store'), [
                'name' => 'Ada Obi',
                'role' => 'Programme lead',
                'photo' => UploadedFile::fake()->image('ada.jpg'),
            ])
            ->assertForbidden();

        $this->actingAs($staff)
            ->put(route('portal.team.update', $teamMember), [
                'name' => 'Changed Name',
                'role' => 'Changed position',
            ])
            ->assertForbidden();

        $this->actingAs($staff)
            ->delete(route('portal.team.destroy', $teamMember))
            ->assertForbidden();

        $this->assertSame('Ada Obi', $teamMember->refresh()->name);
        $this->assertSame('Programme lead', $teamMember->role);

        $this->assertModelExists($teamMember);
    }

    public function test_administrator_sees_an_empty_roster(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.team.index'))
            ->assertOk()
            ->assertSee('No one is on the public team yet.')
            ->assertSee('Add a team member');
    }

    public function test_administrator_can_add_a_team_member(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.team.store'), [
                'name' => 'Ada Obi',
                'role' => 'Programme lead',
                'photo' => UploadedFile::fake()->image('ada.jpg'),
                'facebook_url' => 'https://facebook.com/ada',
            ])
            ->assertRedirect();

        $teamMember = TeamMember::query()->first();

        $this->assertNotNull($teamMember);
        $this->assertSame('Ada Obi', $teamMember->name);
        $this->assertSame('Programme lead', $teamMember->role);
        $this->assertSame('https://facebook.com/ada', $teamMember->facebook_url);
        $disk->assertExists($teamMember->photo);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('Programme lead')
            ->assertSee('https://facebook.com/ada', false);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Ada Obi');

        $this->get(route('volunteer'))
            ->assertOk()
            ->assertSee('Ada Obi');
    }

    public function test_empty_payload_returns_validation_errors(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.team.create'))
            ->post(route('portal.team.store'), [])
            ->assertRedirect(route('portal.team.create'))
            ->assertSessionHasErrors([
                'name' => 'Enter the team member’s name.',
                'role' => 'Enter their position.',
                'photo' => 'Upload a portrait.',
            ]);
    }

    public function test_administrator_can_remove_a_team_member(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $teamMember = TeamMember::factory()->create([
            'name' => 'Ada Obi',
            'photo' => 'team/ada.jpg',
        ]);
        $disk->put('team/ada.jpg', 'portrait');

        $this->actingAs($administrator)
            ->delete(route('portal.team.destroy', $teamMember))
            ->assertRedirect(route('portal.team.index'))
            ->assertSessionHas('status', 'Ada Obi was removed from the team.');

        $this->assertModelMissing($teamMember);
        $disk->assertMissing('team/ada.jpg');

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Ada Obi');
    }

    public function test_the_roster_escapes_dangerous_content(): void
    {
        $administrator = User::factory()->administrator()->create();
        TeamMember::factory()->create([
            'name' => "<script>alert('xss')</script>",
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.team.index'))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertSee('&lt;script&gt;', false);

        $this->get(route('home'))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_administrator_can_edit_a_team_members_name_and_position(): void
    {
        $administrator = User::factory()->administrator()->create();
        $teamMember = TeamMember::factory()->create([
            'name' => 'Ada Obi',
            'role' => 'Programme lead',
            'photo' => 'Charitize/img/stand8.png',
            'facebook_url' => 'https://facebook.com/ada',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.team.edit', $teamMember))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('Programme lead')
            ->assertSee('Position');

        $this->actingAs($administrator)
            ->put(route('portal.team.update', $teamMember), [
                'name' => 'Ngozi Ade',
                'role' => 'Victim support lead',
                'facebook_url' => '',
            ])
            ->assertRedirect(route('portal.team.show', $teamMember))
            ->assertSessionHas('status', 'The team member was updated.');

        $teamMember->refresh();

        $this->assertSame('Ngozi Ade', $teamMember->name);
        $this->assertSame('Victim support lead', $teamMember->role);
        $this->assertSame('Charitize/img/stand8.png', $teamMember->photo);
        $this->assertNull($teamMember->facebook_url);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Ngozi Ade')
            ->assertSee('Victim support lead')
            ->assertDontSee('Ada Obi')
            ->assertDontSee('Programme lead');
    }

    public function test_a_new_portrait_replaces_the_uploaded_picture(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $teamMember = TeamMember::factory()->create([
            'name' => 'Ada Obi',
            'photo' => 'team/ada.jpg',
        ]);
        $disk->put('team/ada.jpg', 'portrait');

        $this->actingAs($administrator)
            ->put(route('portal.team.update', $teamMember), [
                'name' => 'Ada Obi',
                'role' => 'Programme lead',
                'photo' => UploadedFile::fake()->image('new.jpg'),
            ])
            ->assertRedirect(route('portal.team.show', $teamMember));

        $teamMember->refresh();

        $this->assertNotSame('team/ada.jpg', $teamMember->photo);
        $disk->assertMissing('team/ada.jpg');
        $disk->assertExists($teamMember->photo);
    }

    public function test_an_empty_edit_is_rejected(): void
    {
        $administrator = User::factory()->administrator()->create();
        $teamMember = TeamMember::factory()->create();

        $this->actingAs($administrator)
            ->from(route('portal.team.edit', $teamMember))
            ->put(route('portal.team.update', $teamMember), [])
            ->assertRedirect(route('portal.team.edit', $teamMember))
            ->assertSessionHasErrors([
                'name' => 'Enter the team member’s name.',
                'role' => 'Enter their position.',
            ]);
    }
}
