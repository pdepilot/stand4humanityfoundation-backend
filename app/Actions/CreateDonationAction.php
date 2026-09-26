<?php

namespace App\Actions;

use App\Enums\DonationCause;
use App\Enums\DonationCurrency;
use App\Enums\DonationFrequency;
use App\Enums\DonationPaymentMethod;
use App\Enums\DonationPaymentStatus;
use App\Enums\DonationSource;
use App\Events\FrontendActivityRecorded;
use App\Models\Donation;
use Illuminate\Support\Str;

class CreateDonationAction
{
    /**
     * @param  array{
     *     name: string,
     *     email: string,
     *     phone: string,
     *     location?: string|null,
     *     description?: string|null,
     *     amount: float|int|string,
     *     currency: string,
     *     cause: string,
     *     frequency: string,
     *     payment_method: string,
     *     anonymous?: bool
     * }  $data
     */
    public function handle(array $data, DonationSource $source = DonationSource::Public, DonationPaymentStatus $status = DonationPaymentStatus::Paid): Donation
    {
        $cause = DonationCause::from($data['cause']);
        $description = $data['description'] ?? null;

        if (! is_string($description) || trim($description) === '') {
            $description = 'Donation toward '.$cause->label();
        }

        $donation = Donation::query()->create([
            'reference' => 'don-'.Str::lower(bin2hex(random_bytes(6))),
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'location' => $data['location'] ?? null,
            'description' => $description,
            'amount' => $data['amount'],
            'currency' => DonationCurrency::from($data['currency']),
            'cause' => $cause,
            'frequency' => DonationFrequency::from($data['frequency']),
            'payment_method' => DonationPaymentMethod::from($data['payment_method']),
            'payment_status' => $status,
            'provider_reference' => null,
            'paid_at' => $status === DonationPaymentStatus::Paid ? now() : null,
            'anonymous' => (bool) ($data['anonymous'] ?? false),
            'source' => $source,
        ]);

        if ($source === DonationSource::Public && $status === DonationPaymentStatus::Paid) {
            FrontendActivityRecorded::dispatch(
                'donation',
                'New donation received',
                $donation->publicName().' · '.$donation->currency->value.' '.number_format((float) $donation->amount, 0).' · '.$cause->label(),
                route('portal.donors.show', $donation, false),
                $donation->reference,
            );
        }

        return $donation;
    }
}
