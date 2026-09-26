<?php

namespace App\Http\Controllers\Portal;

use App\Actions\CreateContactMessageAction;
use App\Enums\ContactSource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreContactMessageRequest;
use App\Http\Requests\Portal\UpdateContactMessageStatusRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the command-desk contact ledger.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', ContactMessage::class);

        $messages = ContactMessage::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('portal.contacts.index', [
            'user' => $request->user(),
            'messages' => $messages,
            'contactCount' => $messages->total(),
        ]);
    }

    /**
     * Show the form for recording a desk contact.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', ContactMessage::class);

        return view('portal.contacts.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Record a contact entered by an administrator.
     */
    public function store(StoreContactMessageRequest $request, CreateContactMessageAction $createContactMessage): RedirectResponse
    {
        $message = $createContactMessage->handle($request->contactDetails(), ContactSource::Desk);

        return redirect()
            ->route('portal.contacts.show', $message)
            ->with('status', 'The message is on the desk.');
    }

    /**
     * Display a contact message.
     */
    public function show(Request $request, ContactMessage $contactMessage): View
    {
        Gate::authorize('view', $contactMessage);

        return view('portal.contacts.show', [
            'user' => $request->user(),
            'contactMessage' => $contactMessage,
        ]);
    }

    /**
     * Update the contact status.
     */
    public function update(UpdateContactMessageStatusRequest $request, ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->update([
            'status' => $request->status(),
        ]);

        return redirect()
            ->route('portal.contacts.show', $contactMessage)
            ->with('status', 'The message status was updated.');
    }

    /**
     * Remove a contact message.
     */
    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        Gate::authorize('delete', $contactMessage);

        $contactMessage->delete();

        return redirect()
            ->route('portal.contacts.index')
            ->with('status', 'The message was removed from the desk.');
    }
}
