<?php

namespace App\Http\Controllers\Portal;

use App\Actions\CreateNewsletterSubscriberAction;
use App\Enums\NewsletterSource;
use App\Enums\NewsletterStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreNewsletterSubscriberRequest;
use App\Http\Requests\Portal\UpdateNewsletterSubscriberStatusRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NewsletterSubscriberController extends Controller
{
    /**
     * Display the newsletter subscriber ledger.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', NewsletterSubscriber::class);

        $subscribers = NewsletterSubscriber::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('portal.subscribers.index', [
            'user' => $request->user(),
            'subscribers' => $subscribers,
            'subscriberCount' => $subscribers->total(),
            'activeCount' => NewsletterSubscriber::query()
                ->where('status', NewsletterStatus::Active)
                ->count(),
        ]);
    }

    /**
     * Show the form for recording a desk subscriber.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', NewsletterSubscriber::class);

        return view('portal.subscribers.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Record a subscriber entered by an administrator.
     */
    public function store(
        StoreNewsletterSubscriberRequest $request,
        CreateNewsletterSubscriberAction $createNewsletterSubscriber,
    ): RedirectResponse {
        $result = $createNewsletterSubscriber->handle(
            $request->subscriberDetails(),
            NewsletterSource::Desk,
        );

        return redirect()
            ->route('portal.subscribers.show', $result['subscriber'])
            ->with('status', $result['created']
                ? 'The subscriber is on the list.'
                : 'That email was already on the list and is active.');
    }

    /**
     * Display a newsletter subscriber.
     */
    public function show(Request $request, NewsletterSubscriber $newsletterSubscriber): View
    {
        Gate::authorize('view', $newsletterSubscriber);

        return view('portal.subscribers.show', [
            'user' => $request->user(),
            'subscriber' => $newsletterSubscriber,
        ]);
    }

    /**
     * Update the subscription status.
     */
    public function update(
        UpdateNewsletterSubscriberStatusRequest $request,
        NewsletterSubscriber $newsletterSubscriber,
    ): RedirectResponse {
        $status = $request->status();

        $newsletterSubscriber->update([
            'status' => $status,
            'unsubscribed_at' => $status === NewsletterStatus::Unsubscribed ? now() : null,
        ]);

        return redirect()
            ->route('portal.subscribers.show', $newsletterSubscriber)
            ->with('status', 'The subscription status was updated.');
    }

    /**
     * Remove a newsletter subscriber.
     */
    public function destroy(NewsletterSubscriber $newsletterSubscriber): RedirectResponse
    {
        Gate::authorize('delete', $newsletterSubscriber);

        $newsletterSubscriber->delete();

        return redirect()
            ->route('portal.subscribers.index')
            ->with('status', 'The subscriber was removed from the list.');
    }
}
