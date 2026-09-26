<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\NewsArticle;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class NewsControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_news_index_renders_the_journal(): void
    {
        NewsArticle::factory()->create([
            'title' => 'Child protection desk note',
            'slug' => 'child-protection-desk-note',
        ]);

        $this->get(route('news'))
            ->assertOk()
            ->assertSee('News | Stand For Humanity Foundation', false)
            ->assertSee('Child protection desk note')
            ->assertSee('id="news-article-routes"', false)
            ->assertSee('\\u0022child-protection-desk-note\\u0022', false)
            ->assertSee('Charitize/js/news.js?v=journal-routes', false)
            ->assertDontSee('@json', false)
            ->assertSee('ca-pub-4828740366189357', false)
            ->assertDontSee('Charitize/news.html', false);
    }

    public function test_draft_articles_are_hidden_from_the_public_journal(): void
    {
        NewsArticle::factory()->draft()->create([
            'title' => 'Hidden draft note',
            'slug' => 'hidden-draft-note',
        ]);

        $this->get(route('news'))
            ->assertOk()
            ->assertDontSee('Hidden draft note');
    }

    public function test_published_article_renders_from_its_slug(): void
    {
        NewsArticle::factory()->create([
            'title' => 'Child protection and rescue: standing with children in danger',
            'slug' => 'child-protection',
            'excerpt' => 'How we support and rescue children facing abuse.',
            'body' => "## What this work covers\n\nWe protect children in Owerri.\n\n> We stand with those who cannot stand alone.\n\n*This page explains our programme.*",
        ]);

        $this->get(route('news.show', 'child-protection'))
            ->assertOk()
            ->assertSee('Child protection and rescue: standing with children in danger | Stand For Humanity Foundation', false)
            ->assertSee('What this work covers')
            ->assertSee('We stand with those who cannot stand alone')
            ->assertDontSee('href="../about.html"', false);
    }

    public function test_draft_article_slug_is_not_found(): void
    {
        NewsArticle::factory()->draft()->create([
            'slug' => 'draft-only',
        ]);

        $this->get(route('news.show', 'draft-only'))
            ->assertNotFound();
    }

    public function test_unknown_article_slug_is_not_found(): void
    {
        $this->get(route('news.show', 'missing-story'))
            ->assertNotFound();
    }

    public function test_legacy_article_url_redirects_to_the_laravel_route(): void
    {
        $this->get('/Charitize/news/child-protection.html')
            ->assertRedirectToRoute('news.show', 'child-protection');
    }
}
