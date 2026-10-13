<?php

namespace Tests\Feature;

use App\Models\GalleryItem;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class GalleryControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_an_empty_gallery_says_it_is_empty(): void
    {
        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('The gallery is empty.')
            ->assertSee('Gallery | Stand For Humanity Foundation', false);
    }

    public function test_a_video_plays_on_the_gallery_page_with_its_title_and_details(): void
    {
        $item = GalleryItem::factory()->video()->create([
            'title' => 'Saturday meeting',
            'details' => 'Members gathered in Owerri for the weekly stand.',
        ]);

        $this->get(route('gallery.show', $item))
            ->assertOk()
            ->assertSee('Saturday meeting')
            ->assertSee('Members gathered in Owerri for the weekly stand.')
            ->assertSee('<video', false)
            ->assertSee('controls', false)
            ->assertSee($item->mediaUrl(), false)
            ->assertDontSee('youtube.com/watch', false);
    }

    public function test_gallery_text_is_escaped(): void
    {
        $item = GalleryItem::factory()->create([
            'title' => "<script>alert('xss')</script>",
            'details' => '<img src=x onerror=alert(1)>',
        ]);

        $this->get(route('gallery.show', $item))
            ->assertOk()
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false);
    }
}
