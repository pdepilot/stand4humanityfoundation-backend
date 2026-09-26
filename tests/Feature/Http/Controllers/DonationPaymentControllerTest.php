<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\DonationPaymentStatus;
use App\Models\Donation;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DonationPaymentControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_a_verified_paystack_callback_settles_the_gift(): void
    {
        config(['services.paystack.secret' => 'sk_test_secret']);

        $donation = Donation::factory()->create([
            'reference' => 'don-paystack0001',
            'amount' => 5000,
            'currency' => 'NGN',
            'payment_method' => 'paystack',
            'payment_status' => DonationPaymentStatus::Pending,
            'provider_reference' => 'don-paystack0001',
            'paid_at' => null,
        ]);

        Http::fake([
            'api.paystack.co/transaction/verify/*' => Http::response([
                'status' => true,
                'data' => [
                    'status' => 'success',
                    'currency' => 'NGN',
                    'amount' => 500000,
                    'reference' => $donation->reference,
                ],
            ]),
        ]);

        $this->get(route('donations.paystack.callback', ['reference' => $donation->reference]))
            ->assertRedirect(route('donations', ['just' => $donation->reference, 'live' => 1]));

        $this->assertSame(DonationPaymentStatus::Paid, $donation->refresh()->payment_status);

        $this->getJson(route('donations.api.index'))
            ->assertJsonPath('donations.0.id', $donation->reference);
    }

    public function test_a_failed_paystack_charge_stays_off_the_public_ledger(): void
    {
        config(['services.paystack.secret' => 'sk_test_secret']);

        $donation = Donation::factory()->create([
            'reference' => 'don-paystack0002',
            'amount' => 5000,
            'payment_method' => 'paystack',
            'payment_status' => DonationPaymentStatus::Pending,
            'paid_at' => null,
        ]);

        Http::fake([
            'api.paystack.co/transaction/verify/*' => Http::response([
                'status' => true,
                'data' => [
                    'status' => 'abandoned',
                    'currency' => 'NGN',
                    'amount' => 500000,
                ],
            ]),
        ]);

        $this->get(route('donations.paystack.callback', ['reference' => $donation->reference]))
            ->assertRedirect(route('donate'));

        $this->assertSame(DonationPaymentStatus::Failed, $donation->refresh()->payment_status);
        $this->getJson(route('donations.api.index'))
            ->assertJsonPath('donations', []);
    }

    public function test_a_verified_flutterwave_callback_settles_the_gift(): void
    {
        config(['services.flutterwave.secret' => 'flw_test_secret']);

        $donation = Donation::factory()->create([
            'reference' => 'don-flutter00001',
            'amount' => 2500,
            'currency' => 'NGN',
            'payment_method' => 'flutterwave',
            'payment_status' => DonationPaymentStatus::Pending,
            'paid_at' => null,
        ]);

        Http::fake([
            'api.flutterwave.com/v3/transactions/99/verify' => Http::response([
                'status' => 'success',
                'data' => [
                    'status' => 'successful',
                    'currency' => 'NGN',
                    'amount' => 2500,
                    'tx_ref' => $donation->reference,
                ],
            ]),
        ]);

        $this->get(route('donations.flutterwave.callback', [
            'tx_ref' => $donation->reference,
            'transaction_id' => '99',
        ]))->assertRedirect(route('donations', ['just' => $donation->reference, 'live' => 1]));

        $this->assertSame(DonationPaymentStatus::Paid, $donation->refresh()->payment_status);
    }

    public function test_a_paystack_webhook_rejects_a_bad_signature(): void
    {
        config(['services.paystack.secret' => 'sk_test_secret']);

        $this->postJson(route('webhooks.paystack'), ['data' => ['reference' => 'don-x']])
            ->assertUnauthorized();
    }
}
