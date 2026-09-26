<?php

namespace App\Http\Controllers;

use App\Models\FoundationEvent;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display published gatherings on the public events page.
     */
    public function index(): View
    {
        $events = FoundationEvent::query()
            ->published()
            ->orderBy('starts_at')
            ->orderBy('id')
            ->get();

        $featured = $events->firstWhere('is_featured', true) ?? $events->first();

        $upcoming = $featured === null
            ? $events
            : $events->reject(fn (FoundationEvent $event): bool => $event->is($featured))->values();

        return view('site.events', [
            'featured' => $featured,
            'upcoming' => $upcoming,
        ]);
    }
}
