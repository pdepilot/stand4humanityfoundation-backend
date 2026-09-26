<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\NewsletterSource;
use App\Enums\NewsletterStatus;
use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class NewsletterSubscriberControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_home_page_includes_newsletter_form_and_capture_script(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('newsletter-capture-form', false)
            ->assertSee('Charitize/js/newsletter-capture.js', false)
            ->assertSee('/api/newsletter', false);
    }

    public function test_empty_payload_returns_422(): void
    {
        $this->postJson(route('newsletter.api.store'), [])
            ->assertUnprocessable()
            ->assertJsonPath('errors.email.0', 'Enter your email address.');

        $this->assertDatabaseCount('newsletter_subscribers', 0);
    }

    public function test_honeypot_payload_is_rejected_without_saving(): void
    {
        $this->postJson(route('newsletter.api.store'), [
            'email' => 'reader@example.com',
            'website' => 'https://spam.test',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('errors.website.0', 'Unable to accept this subscription.');

        $this->assertDatabaseCount('newsletter_subscribers', 0);
    }

    public function test_valid_email_creates_an_active_public_subscriber_with_detected_location(): void
    {
        Http::fake([
            'https://ipwho.is/*' => Http::response([
                'success' => true,
                'country' => 'Nigeria',
                'country_code' => 'NG',
                'city' => 'Owerri',
            ]),
        ]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '102.89.23.10'])
            ->postJson(route('newsletter.api.store'), [
                'email' => 'Ada@Example.com',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('created', true)
            ->assertJsonMissingPath('subscriber.email');

        $subscriber = NewsletterSubscriber::query()->first();

        $this->assertNotNull($subscriber);
        $this->assertTrue(Str::startsWith($subscriber->reference, 'nws-'));
        $this->assertSame('ada@example.com', $subscriber->email);
        $this->assertSame(NewsletterStatus::Active, $subscriber->status);
        $this->assertSame(NewsletterSource::Public, $subscriber->source);
        $this->assertSame('Nigeria', $subscriber->country);
        $this->assertSame('NG', $subscriber->country_code);
        $this->assertSame('Owerri', $subscriber->city);
        $this->assertSame('102.89.23.10', $subscriber->ip_address);
        $this->assertSame($subscriber->reference, $response->json('subscriber.reference'));
    }

    public function test_private_ip_still_saves_the_subscriber_without_location(): void
    {
        Http::fake();

        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->postJson(route('newsletter.api.store'), [
                'email' => 'local@example.com',
            ])
            ->assertCreated();

        $subscriber = NewsletterSubscriber::query()->first();

        $this->assertNotNull($subscriber);
        $this->assertNull($subscriber->country);
        $this->assertNull($subscriber->city);
        $this->assertNull($subscriber->ip_address);
        Http::assertNothingSent();
    }

    public function test_duplicate_active_email_returns_ok_without_creating_another_row(): void
    {
        Http::fake([
            'https://ipwho.is/*' => Http::response([
                'success' => true,
                'country' => 'Nigeria',
                'country_code' => 'NG',
                'city' => 'Lagos',
            ]),
        ]);

        NewsletterSubscriber::factory()->create([
            'email' => 'reader@example.com',
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '102.89.23.10'])
            ->postJson(route('newsletter.api.store'), [
                'email' => 'reader@example.com',
            ])
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('created', false);

        $this->assertDatabaseCount('newsletter_subscribers', 1);
    }

    public function test_unsubscribed_email_is_reactivated_and_location_is_refreshed(): void
    {
        Http::fake([
            'https://ipwho.is/*' => Http::response([
                'success' => true,
                'country' => 'Ghana',
                'country_code' => 'GH',
                'city' => 'Accra',
            ]),
        ]);

        $subscriber = NewsletterSubscriber::factory()->unsubscribed()->create([
            'email' => 'return@example.com',
            'country' => 'Nigeria',
            'country_code' => 'NG',
            'city' => 'Owerri',
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '154.160.10.20'])
            ->postJson(route('newsletter.api.store'), [
                'email' => 'return@example.com',
            ])
            ->assertOk()
            ->assertJsonPath('created', false);

        $subscriber->refresh();

        $this->assertSame(NewsletterStatus::Active, $subscriber->status);
        $this->assertNull($subscriber->unsubscribed_at);
        $this->assertSame('Ghana', $subscriber->country);
        $this->assertSame('Accra', $subscriber->city);
        $this->assertDatabaseCount('newsletter_subscribers', 1);
    }
}
