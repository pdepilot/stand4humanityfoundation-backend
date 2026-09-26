<?php

namespace App\Http\Controllers\Portal;

use App\Actions\StoreFoundationEventImageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreFoundationEventRequest;
use App\Http\Requests\Portal\UpdateFoundationEventRequest;
use App\Models\FoundationEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display the command-desk events ledger.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', FoundationEvent::class);

        $events = FoundationEvent::query()
            ->orderBy('starts_at')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('portal.events.index', [
            'user' => $request->user(),
            'events' => $events,
            'eventCount' => $events->total(),
            'publishedCount' => FoundationEvent::query()->published()->count(),
            'draftCount' => FoundationEvent::query()->where('is_published', false)->count(),
            'featuredCount' => FoundationEvent::query()->where('is_featured', true)->count(),
        ]);
    }

    /**
     * Show the form for scheduling a gathering.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', FoundationEvent::class);

        return view('portal.events.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Store a gathering from the desk.
     */
    public function store(StoreFoundationEventRequest $request, StoreFoundationEventImageAction $storeFoundationEventImage): RedirectResponse
    {
        $details = $request->eventDetails();
        $details['image'] = $storeFoundationEventImage->store($request->uploadedImage());

        $event = FoundationEvent::query()->create($details);
        $this->keepASingleFeaturedEvent($event);

        return redirect()
            ->route('portal.events.show', $event)
            ->with('status', 'The gathering is on the calendar.');
    }

    /**
     * Display a gathering on the desk.
     */
    public function show(Request $request, FoundationEvent $foundationEvent): View
    {
        Gate::authorize('view', $foundationEvent);

        return view('portal.events.show', [
            'user' => $request->user(),
            'foundationEvent' => $foundationEvent,
        ]);
    }

    /**
     * Show the form for editing a gathering.
     */
    public function edit(Request $request, FoundationEvent $foundationEvent): View
    {
        Gate::authorize('update', $foundationEvent);

        return view('portal.events.edit', [
            'user' => $request->user(),
            'foundationEvent' => $foundationEvent,
        ]);
    }

    /**
     * Update a gathering.
     */
    public function update(
        UpdateFoundationEventRequest $request,
        FoundationEvent $foundationEvent,
        StoreFoundationEventImageAction $storeFoundationEventImage,
    ): RedirectResponse {
        $details = $request->eventDetails();
        $uploadedImage = $request->uploadedImage();

        if ($uploadedImage !== null) {
            $previousImage = $foundationEvent->image;
            $details['image'] = $storeFoundationEventImage->store($uploadedImage);
            $storeFoundationEventImage->delete($previousImage);
        }

        $foundationEvent->update($details);
        $this->keepASingleFeaturedEvent($foundationEvent);

        return redirect()
            ->route('portal.events.show', $foundationEvent)
            ->with('status', 'The gathering was updated.');
    }

    /**
     * Remove a gathering.
     */
    public function destroy(FoundationEvent $foundationEvent, StoreFoundationEventImageAction $storeFoundationEventImage): RedirectResponse
    {
        Gate::authorize('delete', $foundationEvent);

        $imagePath = $foundationEvent->image;
        $foundationEvent->delete();
        $storeFoundationEventImage->delete($imagePath);

        return redirect()
            ->route('portal.events.index')
            ->with('status', 'The gathering was removed from the calendar.');
    }

    /**
     * Keep one featured gathering so the public page has a single lead slot.
     */
    private function keepASingleFeaturedEvent(FoundationEvent $event): void
    {
        if (! $event->is_featured) {
            return;
        }

        FoundationEvent::query()
            ->whereKeyNot($event->id)
            ->where('is_featured', true)
            ->update(['is_featured' => false]);
    }
}
