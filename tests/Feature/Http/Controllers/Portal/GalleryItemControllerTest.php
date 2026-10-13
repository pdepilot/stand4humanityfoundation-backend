<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Enums\GalleryMedia;
use App\Models\GalleryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryItemControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.gallery.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_cannot_manage_the_gallery(): void
    {
        $staff = User::factory()->create();
        $item = GalleryItem::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.gallery.index'))
            ->assertForbidden();

        $this->actingAs($staff)
            ->post(route('portal.gallery.store'), [
                'title' => 'Outreach',
                'details' => 'A public programme photograph.',
                'kind' => GalleryMedia::Image->value,
                'media' => UploadedFile::fake()->image('outreach.jpg'),
            ])
            ->assertForbidden();

        $this->actingAs($staff)
            ->delete(route('portal.gallery.destroy', $item))
            ->assertForbidden();

        $this->assertModelExists($item);
    }

    public function test_administrator_sees_an_empty_gallery(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.gallery.index'))
            ->assertOk()
            ->assertSee('The gallery is empty.')
            ->assertSee('Add a gallery item');
    }

    public function test_administrator_can_add_a_picture_with_title_and_details(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.gallery.store'), [
                'title' => 'Community outreach',
                'details' => 'Volunteers meeting families in Owerri.',
                'kind' => GalleryMedia::Image->value,
                'media' => UploadedFile::fake()->image('outreach.jpg'),
            ])
            ->assertRedirect();

        $item = GalleryItem::query()->first();

        $this->assertNotNull($item);
        $this->assertSame('Community outreach', $item->title);
        $this->assertSame('Volunteers meeting families in Owerri.', $item->details);
        $this->assertSame(GalleryMedia::Image, $item->kind);
        $disk->assertExists($item->path);

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('Community outreach')
            ->assertSee('Volunteers meeting families in Owerri.');

        $this->get(route('gallery.show', $item))
            ->assertOk()
            ->assertSee('Community outreach')
            ->assertSee('Volunteers meeting families in Owerri.')
            ->assertSee($item->mediaUrl(), false);
    }

    public function test_administrator_can_add_a_video_within_15_megabytes(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.gallery.store'), [
                'title' => 'Saturday meeting',
                'details' => 'The weekly gathering in Owerri.',
                'kind' => GalleryMedia::Video->value,
                'media' => UploadedFile::fake()->create('meeting.mp4', 200, 'video/mp4'),
            ])
            ->assertRedirect();

        $item = GalleryItem::query()->first();

        $this->assertNotNull($item);
        $this->assertSame(GalleryMedia::Video, $item->kind);
        $disk->assertExists($item->path);

        $this->get(route('gallery.show', $item))
            ->assertOk()
            ->assertSee('<video', false)
            ->assertSee('Saturday meeting')
            ->assertSee('The weekly gathering in Owerri.');
    }

    public function test_a_video_over_15_megabytes_is_rejected(): void
    {
        Storage::fake('public');
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.gallery.create'))
            ->post(route('portal.gallery.store'), [
                'title' => 'Long recording',
                'details' => 'This file is larger than the gallery allows.',
                'kind' => GalleryMedia::Video->value,
                'media' => UploadedFile::fake()->create('long.mp4', 15361, 'video/mp4'),
            ])
            ->assertRedirect(route('portal.gallery.create'))
            ->assertSessionHasErrors([
                'media' => 'The video must be 15 MB or smaller.',
            ]);

        $this->assertSame(0, GalleryItem::query()->count());
    }

    public function test_empty_payload_returns_the_required_messages(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.gallery.create'))
            ->post(route('portal.gallery.store'), [])
            ->assertRedirect(route('portal.gallery.create'))
            ->assertSessionHasErrors([
                'title' => 'Enter a title.',
                'details' => 'Enter the details.',
                'kind' => 'Choose a picture or a video.',
                'media' => 'Upload a picture or a video.',
            ]);
    }

    public function test_administrator_can_update_the_title_and_details(): void
    {
        $administrator = User::factory()->administrator()->create();
        $item = GalleryItem::factory()->create([
            'title' => 'Old title',
            'details' => 'Old details.',
        ]);

        $this->actingAs($administrator)
            ->put(route('portal.gallery.update', $item), [
                'title' => 'New title',
                'details' => 'New details for visitors.',
                'kind' => GalleryMedia::Image->value,
            ])
            ->assertRedirect(route('portal.gallery.show', $item));

        $item->refresh();

        $this->assertSame('New title', $item->title);
        $this->assertSame('New details for visitors.', $item->details);

        $this->get(route('gallery.show', $item))
            ->assertOk()
            ->assertSee('New title')
            ->assertSee('New details for visitors.');
    }

    public function test_changing_the_type_without_a_new_file_is_rejected(): void
    {
        $administrator = User::factory()->administrator()->create();
        $item = GalleryItem::factory()->create();

        $this->actingAs($administrator)
            ->from(route('portal.gallery.edit', $item))
            ->put(route('portal.gallery.update', $item), [
                'title' => 'Community outreach',
                'details' => 'Volunteers meeting families in Owerri.',
                'kind' => GalleryMedia::Video->value,
            ])
            ->assertRedirect(route('portal.gallery.edit', $item))
            ->assertSessionHasErrors([
                'media' => 'Upload a new file when you change the type.',
            ]);

        $this->assertSame(GalleryMedia::Image, $item->fresh()->kind);
    }

    public function test_administrator_can_remove_a_gallery_item(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $item = GalleryItem::factory()->create([
            'title' => 'Community outreach',
            'path' => 'gallery/outreach.jpg',
        ]);
        $disk->put('gallery/outreach.jpg', 'picture');

        $this->actingAs($administrator)
            ->delete(route('portal.gallery.destroy', $item))
            ->assertRedirect(route('portal.gallery.index'))
            ->assertSessionHas('status', 'Community outreach was removed from the gallery.');

        $this->assertModelMissing($item);
        $disk->assertMissing('gallery/outreach.jpg');

        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('The gallery is empty.')
            ->assertDontSee('Volunteers meeting families in Owerri.');
    }
}
