<?php

namespace App\Actions;

use App\Enums\DonationPaymentMethod;
use App\Enums\DonationPaymentStatus;
use App\Enums\DonationSource;
use App\Events\FrontendActivityRecorded;
use App\Models\Donation;
use Illuminate\Support\Facades\Http;

class VerifyDonationPaymentAction
{
    /**
     * Confirm a pending gift with the payment provider and settle it once.
     */
    public function handle(Donation $donation, ?string $transactionId = null): bool
    {
        if ($donation->payment_status === DonationPaymentStatus::Paid) {
            return true;
        }

        $confirmed = match ($donation->payment_method) {
            DonationPaymentMethod::Paystack => $this->paystackConfirmed($donation),
            DonationPaymentMethod::Flutterwave => $this->flutterwaveConfirmed($donation, $transactionId),
            default => false,
        };

        if (! $confirmed) {
            if ($donation->payment_status === DonationPaymentStatus::Pending) {
                $donation->update(['payment_status' => DonationPaymentStatus::Failed]);
            }

            return false;
        }

        $donation->update([
            'payment_status' => DonationPaymentStatus::Paid,
            'paid_at' => now(),
        ]);

        if ($donation->source === DonationSource::Public) {
            FrontendActivityRecorded::dispatch(
                'donation',
                'New donation received',
                $donation->publicName().' · '.$donation->currency->value.' '.number_format((float) $donation->amount, 0).' · '.$donation->cause->label(),
                route('portal.donors.show', $donation, false),
                $donation->reference,
            );
        }

        return true;
    }

    private function paystackConfirmed(Donation $donation): bool
    {
        $secret = config('services.paystack.secret');

        if (! is_string($secret) || $secret === '') {
            return false;
        }

        $response = Http::withToken($secret)
            ->acceptJson()
            ->connectTimeout(5)
            ->timeout(15)
            ->get(rtrim((string) config('services.paystack.base_url'), '/').'/transaction/verify/'.rawurlencode($donation->reference));

        if ($response->failed()) {
            return false;
        }

        $status = $response->json('data.status');
        $currency = $response->json('data.currency');
        $amount = $response->json('data.amount');

        return $status === 'success'
            && is_string($currency)
            && strtoupper($currency) === $donation->currency->value
            && (int) $amount === (int) round((float) $donation->amount * 100);
    }

    private function flutterwaveConfirmed(Donation $donation, ?string $transactionId): bool
    {
        $secret = config('services.flutterwave.secret');

        if (! is_string($secret) || $secret === '') {
            return false;
        }

        $base = rtrim((string) config('services.flutterwave.base_url'), '/');
        $url = is_string($transactionId) && $transactionId !== ''
            ? $base.'/v3/transactions/'.rawurlencode($transactionId).'/verify'
            : $base.'/v3/transactions/verify_by_reference?tx_ref='.rawurlencode($donation->reference);

        $response = Http::withToken($secret)
            ->acceptJson()
            ->connectTimeout(5)
            ->timeout(15)
            ->get($url);

        if ($response->failed()) {
            return false;
        }

        $status = $response->json('data.status');
        $currency = $response->json('data.currency');
        $amount = $response->json('data.amount');
        $txRef = $response->json('data.tx_ref');

        return $status === 'successful'
            && $txRef === $donation->reference
            && is_string($currency)
            && strtoupper($currency) === $donation->currency->value
            && is_numeric($amount)
            && abs((float) $amount - (float) $donation->amount) < 0.01;
    }
}
