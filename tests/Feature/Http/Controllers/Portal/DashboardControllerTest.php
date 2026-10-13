<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\PortalPermission;
use App\Models\AdminRole;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\IncidentReport;
use App\Models\SiteProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.dashboard'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_authenticated_users_see_the_dashboard(): void
    {
        $user = User::factory()->administrator()->create([
            'name' => 'Portal Administrator',
        ]);

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertOk()
            ->assertSee('Command Desk')
            ->assertSee('Portal Administrator')
            ->assertSee('Administrator')
            ->assertSee('The stand is open.')
            ->assertDontSee('Open the wing')
            ->assertSee('data-count="0"', false);
    }

    public function test_dashboard_donor_count_matches_the_ledger(): void
    {
        $user = User::factory()->administrator()->create();
        Donation::factory()->count(2)->create();

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertSee('data-count="2"', false)
            ->assertSee(route('portal.donors.index', absolute: false), false);
    }

    public function test_dashboard_case_count_matches_the_ledger(): void
    {
        $user = User::factory()->administrator()->create();
        IncidentReport::factory()->count(2)->create();

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertSee('data-count="2"', false)
            ->assertSee(route('portal.cases.index', absolute: false), false);
    }

    public function test_dashboard_contact_count_matches_the_ledger(): void
    {
        $user = User::factory()->administrator()->create();
        ContactMessage::factory()->count(2)->create();

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertSee('data-count="2"', false)
            ->assertSee(route('portal.contacts.index', absolute: false), false);
    }

    public function test_dashboard_escapes_the_authenticated_user_name(): void
    {
        $user = User::factory()->create([
            'name' => "<script>alert('xss')</script>",
        ]);

        $this->actingAs($user)
            ->get(route('portal.dashboard'))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_administrator_can_replace_a_footer_picture_from_the_dashboard(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.dashboard'))
            ->assertOk()
            ->assertSee('Footer pictures');

        $this->actingAs($administrator)
            ->put(route('portal.site.gallery'), [
                'gallery_1' => UploadedFile::fake()->image('footer.jpg'),
            ])
            ->assertRedirectToRoute('portal.dashboard')
            ->assertSessionHas('gallery_status', 'The footer pictures were updated.');

        $profile = SiteProfile::query()->first();

        $this->assertNotNull($profile);
        $disk->assertExists($profile->gallery_1);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee($profile->gallery()[0]['url'], false)
            ->assertDontSee('Charitize/img/gallery-1.jpg', false);
    }

    public function test_a_blank_footer_upload_keeps_the_current_picture(): void
    {
        $administrator = User::factory()->administrator()->create();
        SiteProfile::current();

        $this->actingAs($administrator)
            ->put(route('portal.site.gallery'), [])
            ->assertRedirectToRoute('portal.dashboard');

        $this->assertNull(SiteProfile::current()->gallery_1);
    }

    public function test_a_footer_picture_must_be_an_image(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.dashboard'))
            ->put(route('portal.site.gallery'), [
                'gallery_1' => UploadedFile::fake()->create('notes.txt', 20, 'text/plain'),
            ])
            ->assertRedirect(route('portal.dashboard'))
            ->assertSessionHasErrors([
                'gallery_1' => 'Each footer picture must be an image file.',
            ]);
    }

    public function test_an_administrator_without_site_access_cannot_change_footer_pictures(): void
    {
        $role = AdminRole::factory()->create([
            'name' => 'News only',
            'permissions' => [PortalPermission::News->value],
        ]);
        $administrator = User::factory()->administrator()->create([
            'admin_role_id' => $role->id,
        ]);

        $this->actingAs($administrator)
            ->put(route('portal.site.gallery'), [])
            ->assertForbidden()
            ->assertSee(PortalPermission::DENIED_MESSAGE);
    }
}
