<?php

namespace App\Actions;

use App\Enums\DonationPaymentMethod;
use App\Enums\DonationPaymentStatus;
use App\Enums\DonationSource;
use App\Models\Donation;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class StartDonationCheckoutAction
{
    public function __construct(private CreateDonationAction $createDonation) {}

    /**
     * Open a Paystack or Flutterwave checkout for a public gift.
     *
     * @param  array<string, mixed>  $data
     * @return array{donation: Donation, checkout_url: string}|null
     */
    public function handle(array $data): ?array
    {
        $donation = $this->createDonation->handle($data, DonationSource::Public, DonationPaymentStatus::Pending);
        $donation->update(['provider_reference' => $donation->reference]);

        $checkoutUrl = match ($donation->payment_method) {
            DonationPaymentMethod::Paystack => $this->paystackUrl($donation),
            DonationPaymentMethod::Flutterwave => $this->flutterwaveUrl($donation),
            default => null,
        };

        if (! is_string($checkoutUrl) || $checkoutUrl === '') {
            $donation->update(['payment_status' => DonationPaymentStatus::Failed]);

            return null;
        }

        return [
            'donation' => $donation,
            'checkout_url' => $checkoutUrl,
        ];
    }

    private function paystackUrl(Donation $donation): ?string
    {
        $secret = config('services.paystack.secret');

        if (! is_string($secret) || $secret === '') {
            return null;
        }

        $response = Http::withToken($secret)
            ->acceptJson()
            ->connectTimeout(5)
            ->timeout(15)
            ->post(rtrim((string) config('services.paystack.base_url'), '/').'/transaction/initialize', [
                'email' => $donation->email,
                'amount' => (int) round((float) $donation->amount * 100),
                'currency' => $donation->currency->value,
                'reference' => $donation->reference,
                'callback_url' => route('donations.paystack.callback'),
                'metadata' => [
                    'donation_reference' => $donation->reference,
                    'cause' => $donation->cause->value,
                ],
            ]);

        if ($response->failed()) {
            return null;
        }

        $url = $response->json('data.authorization_url');

        return is_string($url) ? $url : null;
    }

    private function flutterwaveUrl(Donation $donation): ?string
    {
        $secret = config('services.flutterwave.secret');

        if (! is_string($secret) || $secret === '') {
            return null;
        }

        try {
            $response = Http::withToken($secret)
                ->acceptJson()
                ->connectTimeout(5)
                ->timeout(15)
                ->post(rtrim((string) config('services.flutterwave.base_url'), '/').'/v3/payments', [
                    'tx_ref' => $donation->reference,
                    'amount' => (float) $donation->amount,
                    'currency' => $donation->currency->value,
                    'redirect_url' => route('donations.flutterwave.callback'),
                    'customer' => [
                        'email' => $donation->email,
                        'name' => $donation->name,
                        'phonenumber' => $donation->phone,
                    ],
                    'customizations' => [
                        'title' => 'Stand For Humanity Foundation',
                        'description' => $donation->cause->label(),
                    ],
                ])
                ->throw();
        } catch (RequestException) {
            return null;
        }

        $url = $response->json('data.link');

        return is_string($url) ? $url : null;
    }
}
