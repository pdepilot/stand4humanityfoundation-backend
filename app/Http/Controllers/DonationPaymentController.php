<?php

namespace App\Http\Controllers;

use App\Actions\VerifyDonationPaymentAction;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DonationPaymentController extends Controller
{
    /**
     * Paystack returns the donor here after checkout.
     */
    public function paystack(Request $request, VerifyDonationPaymentAction $verifyDonationPayment): RedirectResponse
    {
        return $this->finish($request->string('reference')->toString(), $verifyDonationPayment);
    }

    /**
     * Flutterwave returns the donor here after checkout.
     */
    public function flutterwave(Request $request, VerifyDonationPaymentAction $verifyDonationPayment): RedirectResponse
    {
        $reference = $request->string('tx_ref')->toString();
        $transactionId = $request->string('transaction_id')->toString();

        return $this->finish($reference, $verifyDonationPayment, $transactionId !== '' ? $transactionId : null);
    }

    /**
     * Confirm a Paystack webhook by verifying the charge again.
     */
    public function paystackWebhook(Request $request, VerifyDonationPaymentAction $verifyDonationPayment): Response
    {
        $secret = config('services.paystack.secret');
        $signature = (string) $request->header('x-paystack-signature', '');

        if (! is_string($secret) || $secret === '' || ! hash_equals(hash_hmac('sha512', $request->getContent(), $secret), $signature)) {
            return response('Invalid signature.', 401);
        }

        $reference = $request->input('data.reference');

        if (is_string($reference)) {
            $donation = Donation::query()->where('reference', $reference)->first();

            if ($donation instanceof Donation) {
                $verifyDonationPayment->handle($donation);
            }
        }

        return response('OK');
    }

    /**
     * Confirm a Flutterwave webhook by verifying the charge again.
     */
    public function flutterwaveWebhook(Request $request, VerifyDonationPaymentAction $verifyDonationPayment): Response
    {
        $hash = config('services.flutterwave.webhook_hash');
        $signature = (string) $request->header('verif-hash', '');

        if (! is_string($hash) || $hash === '' || ! hash_equals($hash, $signature)) {
            return response('Invalid signature.', 401);
        }

        $reference = $request->input('data.tx_ref');
        $transactionId = $request->input('data.id');

        if (is_string($reference)) {
            $donation = Donation::query()->where('reference', $reference)->first();

            if ($donation instanceof Donation) {
                $verifyDonationPayment->handle($donation, is_scalar($transactionId) ? (string) $transactionId : null);
            }
        }

        return response('OK');
    }

    private function finish(string $reference, VerifyDonationPaymentAction $verifyDonationPayment, ?string $transactionId = null): RedirectResponse
    {
        $donation = Donation::query()->where('reference', $reference)->first();

        if (! $donation instanceof Donation || ! $verifyDonationPayment->handle($donation, $transactionId)) {
            return redirect()
                ->route('donate')
                ->with('payment_error', 'The payment was not completed. You have not been charged a recorded gift.');
        }

        return redirect()->route('donations', [
            'just' => $donation->reference,
            'live' => 1,
        ]);
    }
}
