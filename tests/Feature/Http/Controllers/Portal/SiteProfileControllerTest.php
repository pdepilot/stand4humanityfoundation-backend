<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Models\SiteProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteProfileControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.site.edit'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_cannot_change_the_public_site(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.site.edit'))
            ->assertForbidden();

        $this->actingAs($staff)
            ->put(route('portal.site.update'), $this->validPayload())
            ->assertForbidden();
    }

    public function test_administrator_can_change_the_office_social_links_and_footer_gallery(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->put(route('portal.site.update'), [
                ...$this->validPayload(),
                'gallery_1' => UploadedFile::fake()->image('gallery.jpg'),
            ])
            ->assertRedirect(route('portal.site.edit'))
            ->assertSessionHas('status', 'The public site details were updated.');

        $profile = SiteProfile::query()->first();

        $this->assertNotNull($profile);
        $this->assertSame('+234 803 000 0000', $profile->phone);
        $this->assertSame('https://facebook.com/stand', $profile->facebook_url);
        $this->assertNull($profile->instagram_url);
        $disk->assertExists($profile->gallery_1);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('+234 803 000 0000')
            ->assertSee('12 Wetheral Road, Owerri')
            ->assertSee('https://facebook.com/stand', false)
            ->assertSee('Monday desk hours')
            ->assertDontSee('fab fa-instagram', false);

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('desk@example.com');
    }

    public function test_empty_payload_returns_the_required_messages(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.site.edit'))
            ->put(route('portal.site.update'), [])
            ->assertRedirect(route('portal.site.edit'))
            ->assertSessionHasErrors([
                'address' => 'Enter the office address.',
                'phone' => 'Enter the public phone number.',
                'email' => 'Enter the public email address.',
            ]);
    }

    /**
     * @return array<string, string>
     */
    private function validPayload(): array
    {
        return [
            'address' => '12 Wetheral Road, Owerri',
            'phone' => '+234 803 000 0000',
            'email' => 'desk@example.com',
            'facebook_url' => 'https://facebook.com/stand',
            'weekday_label' => 'Monday - Friday',
            'weekday_hours' => 'Monday desk hours',
            'saturday_label' => 'Saturday',
            'saturday_hours' => '09:00 am - 12:00 pm',
            'extra_label' => 'Emergency Hotline',
            'extra_hours' => '24/7',
        ];
    }
}
