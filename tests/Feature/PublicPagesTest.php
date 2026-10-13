<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use LazilyRefreshDatabase;

    #[TestWith(['about', 'About | Stand For Humanity Foundation'])]
    #[TestWith(['services', 'Our Services | Stand For Humanity Foundation'])]
    #[TestWith(['donations', 'Donations Ledger | Stand For Humanity Foundation'])]
    #[TestWith(['donate', 'Donate | Stand For Humanity Foundation'])]
    #[TestWith(['news', 'News | Stand For Humanity Foundation'])]
    #[TestWith(['privacy', 'Privacy Policy | Stand For Humanity Foundation'])]
    #[TestWith(['terms', 'Terms of Use | Stand For Humanity Foundation'])]
    #[TestWith(['events', 'Events | Stand For Humanity Foundation'])]
    #[TestWith(['gallery', 'Gallery | Stand For Humanity Foundation'])]
    #[TestWith(['volunteer', 'Volunteer & Leadership | Stand For Humanity Foundation'])]
    #[TestWith(['our-stand', 'Our Stand | Stand For Humanity Foundation'])]
    #[TestWith(['report', 'Report Incident | Stand For Humanity Foundation'])]
    #[TestWith(['mission', 'Our Mission & Programs | Stand For Humanity Foundation'])]
    #[TestWith(['contact', 'Contact Us | Stand For Humanity Foundation'])]
    #[TestWith(['help', 'Get Help | Stand For Humanity Foundation'])]
    public function test_public_page_renders_without_legacy_charitize_urls(string $name, string $title): void
    {
        $this->get(route($name))
            ->assertSee($title, false)
            ->assertDontSee('Charitize/about.html', false)
            ->assertDontSee('href="about.html"', false);
    }

    #[TestWith(['about.html', 'about'])]
    #[TestWith(['services.html', 'services'])]
    #[TestWith(['service.html', 'services'])]
    #[TestWith(['donations.html', 'donations'])]
    #[TestWith(['donation.html', 'donate'])]
    #[TestWith(['news.html', 'news'])]
    #[TestWith(['event.html', 'events'])]
    #[TestWith(['team.html', 'volunteer'])]
    #[TestWith(['testimonial.html', 'our-stand'])]
    #[TestWith(['report-live.html', 'report'])]
    #[TestWith(['feature.html', 'mission'])]
    #[TestWith(['contact.html', 'contact'])]
    #[TestWith(['404.html', 'help'])]
    public function test_legacy_charitize_page_redirects_to_the_laravel_route(string $file, string $name): void
    {
        $this->get('/Charitize/'.$file)
            ->assertRedirectToRoute($name);
    }
}
