<?php

namespace App\Http\Controllers\Portal;

use App\Actions\CreateDonationAction;
use App\Enums\DonationSource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreDonationRequest;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DonorController extends Controller
{
    /**
     * Display the command-desk donor ledger.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Donation::class);

        $donations = Donation::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('portal.donors.index', [
            'user' => $request->user(),
            'donations' => $donations,
            'donorCount' => $donations->total(),
        ]);
    }

    /**
     * Show the form for recording a desk donation.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', Donation::class);

        return view('portal.donors.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Record a donation entered by an administrator.
     */
    public function store(StoreDonationRequest $request, CreateDonationAction $createDonation): RedirectResponse
    {
        $donation = $createDonation->handle($request->donationDetails(), DonationSource::Desk);

        return redirect()
            ->route('portal.donors.show', $donation)
            ->with('status', 'The donation is on the ledger.');
    }

    /**
     * Display a donor record.
     */
    public function show(Request $request, Donation $donation): View
    {
        Gate::authorize('view', $donation);

        return view('portal.donors.show', [
            'user' => $request->user(),
            'donation' => $donation,
        ]);
    }

    /**
     * Remove a donor record.
     */
    public function destroy(Donation $donation): RedirectResponse
    {
        Gate::authorize('delete', $donation);

        $donation->delete();

        return redirect()
            ->route('portal.donors.index')
            ->with('status', 'The donor record was removed.');
    }
}
