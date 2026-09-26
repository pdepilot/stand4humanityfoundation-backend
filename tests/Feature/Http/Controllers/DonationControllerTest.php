<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\DonationSource;
use App\Models\Donation;
use App\Models\DonationSetting;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class DonationControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_donate_page_includes_csrf_token_and_capture_script(): void
    {
        $this->get(route('donate'))
            ->assertSee('name="csrf-token"', false)
            ->assertSee('Charitize/js/donate-capture.js', false)
            ->assertSee('id="donationCaptureForm"', false);
    }

    public function test_public_ledger_returns_an_empty_list(): void
    {
        $this->getJson(route('donations.api.index'))
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('donations', [])
            ->assertJsonPath('goal', 0)
            ->assertJsonPath('raised', 0)
            ->assertJsonPath('percent', 0)
            ->assertJsonPath('goals.child-protection.goal', 0)
            ->assertJsonPath('goals.rescue.goal', 0)
            ->assertJsonPath('goals.victim-support.goal', 0)
            ->assertJsonPath('spent', 0)
            ->assertJsonPath('bank', 0);
    }

    public function test_public_ledger_lists_newest_donations_first(): void
    {
        $this->travelTo('2026-09-23 12:00:00');

        $older = Donation::factory()->create([
            'reference' => 'don-older000001',
            'created_at' => now()->subDay(),
        ]);
        $newer = Donation::factory()->create([
            'reference' => 'don-newer000001',
            'created_at' => now(),
        ]);

        $this->getJson(route('donations.api.index'))
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('donations.0.id', $newer->reference)
            ->assertJsonPath('donations.1.id', $older->reference);
    }

    public function test_public_ledger_hides_an_anonymous_donor_name(): void
    {
        Donation::factory()->anonymous()->create([
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'reference' => 'don-anon0000001',
        ]);

        $this->getJson(route('donations.api.index'))
            ->assertOk()
            ->assertJsonPath('donations.0.name', 'Anonymous Donor')
            ->assertJsonPath('donations.0.anonymous', true)
            ->assertJsonMissingPath('donations.0.email')
            ->assertJsonMissingPath('donations.0.source')
            ->assertDontSee('Ada Obi')
            ->assertDontSee('ada@example.com');
    }

    public function test_public_ledger_includes_the_administrator_goal(): void
    {
        DonationSetting::factory()->create([
            'child_protection_goal' => 100000,
            'rescue_goal' => 0,
            'victim_support_goal' => 0,
            'spent_amount' => 10000,
        ]);
        Donation::factory()->create([
            'amount' => 40000,
            'currency' => 'NGN',
            'cause' => 'child-protection',
        ]);

        $this->getJson(route('donations.api.index'))
            ->assertOk()
            ->assertJsonPath('goal', 100000)
            ->assertJsonPath('raised', 40000)
            ->assertJsonPath('percent', 40)
            ->assertJsonPath('spent', 10000)
            ->assertJsonPath('bank', 30000)
            ->assertJsonPath('goals.child-protection.goal', 100000)
            ->assertJsonPath('goals.child-protection.raised', 40000)
            ->assertJsonPath('goals.child-protection.percent', 40)
            ->assertJsonPath('goals.rescue.goal', 0);
    }

    public function test_legacy_charitize_ledger_path_uses_the_laravel_api(): void
    {
        Donation::factory()->create([
            'reference' => 'don-legacy00001',
            'name' => 'Chinedu Okeke',
        ]);

        $this->getJson('/Charitize/api/donations.php')
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('donations.0.id', 'don-legacy00001')
            ->assertJsonPath('donations.0.name', 'Chinedu Okeke');
    }

    public function test_empty_payload_returns_422_with_required_field_messages(): void
    {
        $this->postJson(route('donations.api.store'), [])
            ->assertUnprocessable()
            ->assertJsonPath('errors.name.0', 'Enter the donor name.')
            ->assertJsonPath('errors.email.0', 'Enter the donor email.')
            ->assertJsonPath('errors.phone.0', 'Enter the donor phone number.')
            ->assertJsonPath('errors.amount.0', 'Enter a donation amount.')
            ->assertJsonPath('errors.currency.0', 'Choose a currency.')
            ->assertJsonPath('errors.cause.0', 'Choose a donation cause.')
            ->assertJsonPath('errors.frequency.0', 'Choose how often this gift is given.')
            ->assertJsonPath('errors.payment_method.0', 'Choose a payment method.');

        $this->assertDatabaseCount('donations', 0);
    }

    public function test_returns_422_when_the_amount_is_below_one(): void
    {
        $this->postJson(route('donations.api.store'), $this->validPayload([
            'amount' => 0,
        ]))
            ->assertUnprocessable()
            ->assertJsonPath('errors.amount.0', 'Enter a donation amount of at least 1.');

        $this->assertDatabaseCount('donations', 0);
    }

    public function test_returns_422_when_the_email_is_invalid(): void
    {
        $this->postJson(route('donations.api.store'), $this->validPayload([
            'email' => 'not-an-email',
        ]))
            ->assertUnprocessable()
            ->assertJsonPath('errors.email.0', 'Enter a valid email address.');

        $this->assertDatabaseCount('donations', 0);
    }

    public function test_returns_422_when_the_cause_is_not_supported(): void
    {
        $this->postJson(route('donations.api.store'), $this->validPayload([
            'cause' => 'not-a-cause',
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['cause']);

        $this->assertDatabaseCount('donations', 0);
    }

    public function test_valid_payload_creates_a_public_donation_and_returns_201(): void
    {
        $this->freezeTime();
        $this->fakePaystack();

        $response = $this->postJson(route('donations.api.store'), $this->validPayload());

        $response
            ->assertCreated()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('donation.name', 'Ada Obi')
            ->assertJsonPath('donation.amount', 5000)
            ->assertJsonPath('donation.currency', 'NGN')
            ->assertJsonPath('donation.cause', 'child-protection')
            ->assertJsonPath('donation.anonymous', false)
            ->assertJsonPath('checkoutUrl', 'https://checkout.paystack.com/test')
            ->assertJsonMissingPath('donation.email')
            ->assertJsonMissingPath('donation.source');

        $donation = Donation::query()->first();

        $this->assertNotNull($donation);
        $this->assertTrue(Str::startsWith($donation->reference, 'don-'));
        $this->assertSame($donation->reference, $response->json('donation.id'));
        $this->assertSame(DonationSource::Public, $donation->source);
        $this->assertSame('Ada Obi', $donation->name);
        $this->assertSame('ada@example.com', $donation->email);
        $this->assertSame('08012345678', $donation->phone);
        $this->assertSame('Owerri, Imo State', $donation->location);
        $this->assertSame('For rescue work', $donation->description);
        $this->assertSame('5000.00', $donation->amount);
        $this->assertSame($donation->created_at?->toIso8601String(), $response->json('donation.createdAt'));
    }

    public function test_missing_description_uses_the_cause_label(): void
    {
        $this->fakePaystack();

        $this->postJson(route('donations.api.store'), $this->validPayload([
            'description' => '',
            'cause' => 'rescue',
        ]))
            ->assertCreated()
            ->assertJsonPath('donation.description', 'Donation toward Emergency Rescue & Intervention');

        $this->assertDatabaseHas('donations', [
            'email' => 'ada@example.com',
            'description' => 'Donation toward Emergency Rescue & Intervention',
        ]);
    }

    public function test_anonymous_gift_hides_the_name_in_the_created_response(): void
    {
        $this->fakePaystack();

        $this->postJson(route('donations.api.store'), $this->validPayload([
            'anonymous' => true,
        ]))
            ->assertCreated()
            ->assertJsonPath('donation.name', 'Anonymous Donor')
            ->assertJsonPath('donation.anonymous', true)
            ->assertDontSee('Ada Obi');

        $this->assertDatabaseHas('donations', [
            'name' => 'Ada Obi',
            'anonymous' => true,
            'source' => DonationSource::Public->value,
        ]);
    }

    public function test_unexpected_source_and_reference_keys_are_ignored(): void
    {
        $this->fakePaystack();

        $response = $this->postJson(route('donations.api.store'), $this->validPayload([
            'source' => 'desk',
            'reference' => 'don-hacked00001',
            'id' => 999,
        ]));

        $response->assertCreated();

        $donation = Donation::query()->first();

        $this->assertNotNull($donation);
        $this->assertSame(DonationSource::Public, $donation->source);
        $this->assertNotSame('don-hacked00001', $donation->reference);
        $this->assertNotSame(999, $donation->id);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'phone' => '08012345678',
            'location' => 'Owerri, Imo State',
            'description' => 'For rescue work',
            'amount' => 5000,
            'currency' => 'NGN',
            'cause' => 'child-protection',
            'frequency' => 'one-time',
            'paymentMethod' => 'paystack',
            'anonymous' => false,
        ], $overrides);
    }

    private function fakePaystack(): void
    {
        config(['services.paystack.secret' => 'sk_test_secret']);

        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/test',
                    'reference' => 'don-test',
                ],
            ]),
        ]);
    }
}
