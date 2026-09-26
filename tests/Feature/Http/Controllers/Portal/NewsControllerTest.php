<?php

namespace Tests\Feature\Http\Controllers\Portal;

use App\Models\NewsArticle;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('portal.news.index'))
            ->assertRedirect(route('portal.login'));
    }

    public function test_staff_are_forbidden_from_the_news_desk(): void
    {
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->get(route('portal.news.index'))
            ->assertForbidden();
    }

    public function test_administrator_sees_the_empty_journal_ledger(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.news.index'))
            ->assertOk()
            ->assertSee('Programme journal')
            ->assertSee('No notes on the desk yet.')
            ->assertSee('Write a note')
            ->assertSee('AdSense checklist');
    }

    public function test_administrator_sees_notes_on_the_ledger(): void
    {
        $administrator = User::factory()->administrator()->create();
        NewsArticle::factory()->create([
            'title' => 'Child protection briefing',
            'slug' => 'child-protection-briefing',
        ]);
        NewsArticle::factory()->draft()->create([
            'title' => 'Draft advocacy note',
            'slug' => 'draft-advocacy-note',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.news.index'))
            ->assertOk()
            ->assertSee('Child protection briefing')
            ->assertSee('Draft advocacy note')
            ->assertSee('Published')
            ->assertSee('Draft')
            ->assertSee('2 notes');
    }

    public function test_journal_ledger_escapes_dangerous_content(): void
    {
        $administrator = User::factory()->administrator()->create();
        NewsArticle::factory()->create([
            'title' => "<script>alert('xss')</script>",
            'slug' => 'safe-slug',
            'excerpt' => '<img src=x onerror=alert(1)>',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.news.index'))
            ->assertDontSee("<script>alert('xss')</script>", false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_administrator_can_view_the_write_form(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->get(route('portal.news.create'))
            ->assertOk()
            ->assertSee('Write a note')
            ->assertSee('Note details')
            ->assertSee('Body (Markdown)')
            ->assertSee('Before you publish');
    }

    public function test_staff_cannot_submit_a_note(): void
    {
        Storage::fake('public');
        $staff = User::factory()->create();

        $this->actingAs($staff)
            ->post(route('portal.news.store'), $this->validPayload())
            ->assertForbidden();

        $this->assertDatabaseCount('news_articles', 0);
    }

    public function test_empty_note_payload_returns_validation_errors(): void
    {
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.news.create'))
            ->post(route('portal.news.store'), [])
            ->assertRedirect(route('portal.news.create'))
            ->assertSessionHasErrors([
                'title',
                'slug',
                'excerpt',
                'body',
                'image',
                'image_alt',
                'published_at',
            ]);

        $this->assertDatabaseCount('news_articles', 0);
    }

    public function test_administrator_can_publish_a_note(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->from(route('portal.news.create'))
            ->post(route('portal.news.store'), $this->validPayload())
            ->assertRedirectToRoute('portal.news.show', 'community-safety-brief')
            ->assertSessionHas('status', 'The note is on the journal.');

        $article = NewsArticle::query()->first();

        $this->assertNotNull($article);
        $this->assertSame('Community safety brief', $article->title);
        $this->assertSame('community-safety-brief', $article->slug);
        $this->assertTrue($article->is_published);
        $this->assertTrue($article->is_popular);
        $this->assertSame(['Safety', 'Owerri'], $article->tagList());
        $this->assertTrue($article->hasUploadedImage());
        $disk->assertExists($article->image);

        $this->get(route('news.show', $article->slug))
            ->assertOk()
            ->assertSee('Community safety brief')
            ->assertSee($article->imageUrl(), false);
    }

    public function test_slug_is_generated_from_title_when_omitted(): void
    {
        Storage::fake('public');
        $administrator = User::factory()->administrator()->create();

        $this->actingAs($administrator)
            ->post(route('portal.news.store'), $this->validPayload([
                'title' => 'Volunteer conduct in Owerri',
                'slug' => '',
            ]))
            ->assertRedirect();

        $this->assertDatabaseHas('news_articles', [
            'slug' => 'volunteer-conduct-in-owerri',
            'title' => 'Volunteer conduct in Owerri',
        ]);
    }

    public function test_administrator_can_view_a_note_record(): void
    {
        $administrator = User::factory()->administrator()->create();
        $article = NewsArticle::factory()->create([
            'title' => 'Report Live hours',
            'slug' => 'report-live-hours',
            'excerpt' => 'How to file an urgent case.',
        ]);

        $this->actingAs($administrator)
            ->get(route('portal.news.show', $article))
            ->assertOk()
            ->assertSee('Report Live hours')
            ->assertSee('report-live-hours')
            ->assertSee('How to file an urgent case.')
            ->assertSee('Reader preview')
            ->assertSee('Edit note')
            ->assertSee('Remove note');
    }

    public function test_administrator_can_update_a_note(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $article = NewsArticle::factory()->create([
            'title' => 'Old title',
            'slug' => 'old-title',
            'is_popular' => false,
        ]);

        $this->actingAs($administrator)
            ->from(route('portal.news.edit', $article))
            ->put(route('portal.news.update', $article), $this->validPayload([
                'title' => 'Updated protection note',
                'slug' => 'updated-protection-note',
                'is_popular' => '1',
            ]))
            ->assertRedirectToRoute('portal.news.show', 'updated-protection-note')
            ->assertSessionHas('status', 'The note was updated.');

        $article->refresh();

        $this->assertSame('Updated protection note', $article->title);
        $this->assertSame('updated-protection-note', $article->slug);
        $this->assertTrue($article->is_popular);
        $this->assertTrue($article->hasUploadedImage());
        $disk->assertExists($article->image);
    }

    public function test_administrator_can_update_a_note_without_replacing_the_cover(): void
    {
        Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $article = NewsArticle::factory()->create([
            'title' => 'Keep cover note',
            'slug' => 'keep-cover-note',
            'image' => 'event-3.jpg',
        ]);

        $payload = $this->validPayload([
            'title' => 'Keep cover note updated',
            'slug' => 'keep-cover-note',
        ]);
        unset($payload['image']);

        $this->actingAs($administrator)
            ->put(route('portal.news.update', $article), $payload)
            ->assertRedirectToRoute('portal.news.show', 'keep-cover-note');

        $this->assertSame('event-3.jpg', $article->fresh()->image);
    }

    public function test_staff_cannot_update_a_note(): void
    {
        Storage::fake('public');
        $staff = User::factory()->create();
        $article = NewsArticle::factory()->create([
            'title' => 'Protected title',
        ]);

        $this->actingAs($staff)
            ->put(route('portal.news.update', $article), $this->validPayload([
                'title' => 'Hacked title',
            ]))
            ->assertForbidden();

        $this->assertSame('Protected title', $article->fresh()->title);
    }

    public function test_administrator_can_remove_a_note(): void
    {
        $disk = Storage::fake('public');
        $administrator = User::factory()->administrator()->create();
        $path = UploadedFile::fake()->image('remove-cover.jpg')->store('news', 'public');
        $article = NewsArticle::factory()->create([
            'image' => $path,
        ]);

        $this->actingAs($administrator)
            ->from(route('portal.news.show', $article))
            ->delete(route('portal.news.destroy', $article))
            ->assertRedirectToRoute('portal.news.index')
            ->assertSessionHas('status', 'The note was removed from the journal.');

        $this->assertModelMissing($article);
        $disk->assertMissing($path);
    }

    public function test_staff_cannot_remove_a_note(): void
    {
        $staff = User::factory()->create();
        $article = NewsArticle::factory()->create();

        $this->actingAs($staff)
            ->delete(route('portal.news.destroy', $article))
            ->assertForbidden();

        $this->assertModelExists($article);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Community safety brief',
            'slug' => 'community-safety-brief',
            'excerpt' => 'A short programme note on community safety in Owerri.',
            'body' => "## Standing with neighbours\n\nWe help families seek safety and appropriate assistance in Owerri, Imo State. This programme note explains how neighbours can report a concern without turning a crisis into public content.\n\nUse Report Live when a person is missing or in danger, and keep a child's photograph off public channels.\n\n> We stand with those who cannot stand alone.\n\n*This page explains our programme. It is not a report of a specific case.*",
            'image' => UploadedFile::fake()->image('community-cover.jpg', 1200, 800),
            'image_alt' => 'Community awareness walk in Owerri',
            'author' => 'Foundation desk',
            'reading_minutes' => 4,
            'tags' => 'Safety, Owerri',
            'search_keywords' => 'community safety owerri',
            'is_published' => '1',
            'is_popular' => '1',
            'published_at' => now()->subHour()->format('Y-m-d H:i:s'),
        ], $overrides);
    }
}
