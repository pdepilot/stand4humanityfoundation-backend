<?php

namespace App\Http\Controllers;

use App\Actions\CreateDonationAction;
use App\Actions\StartDonationCheckoutAction;
use App\Enums\DonationPaymentMethod;
use App\Enums\DonationSource;
use App\Http\Requests\StoreDonationRequest;
use App\Models\Donation;
use App\Support\DonationProgress;
use Illuminate\Http\JsonResponse;

class DonationController extends Controller
{
    /**
     * List donations for the public ledger.
     */
    public function index(): JsonResponse
    {
        $donations = Donation::query()
            ->settled()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Donation $donation): array => $donation->toPublicArray())
            ->values();

        return response()->json([
            'ok' => true,
            'donations' => $donations,
            ...DonationProgress::current()->toArray(),
        ]);
    }

    /**
     * Record a donation from the public donate form.
     */
    public function store(StoreDonationRequest $request, CreateDonationAction $createDonation, StartDonationCheckoutAction $startDonationCheckout): JsonResponse
    {
        $details = $request->donationDetails();
        $method = DonationPaymentMethod::from($details['payment_method']);

        if ($method === DonationPaymentMethod::Paystack || $method === DonationPaymentMethod::Flutterwave) {
            $checkout = $startDonationCheckout->handle($details);

            if ($checkout === null) {
                return response()->json([
                    'ok' => false,
                    'message' => 'The payment desk could not open. Add the Paystack or Flutterwave secret key and try again.',
                ], 502);
            }

            return response()->json([
                'ok' => true,
                'checkoutUrl' => $checkout['checkout_url'],
                'donation' => $checkout['donation']->toPublicArray(),
            ], 201);
        }

        $donation = $createDonation->handle($details, DonationSource::Public);

        return response()->json([
            'ok' => true,
            'donation' => $donation->toPublicArray(),
        ], 201);
    }
}
