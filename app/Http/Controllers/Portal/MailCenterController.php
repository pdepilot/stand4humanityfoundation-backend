<?php

namespace App\Http\Controllers\Portal;

use App\Actions\SendFoundationMailAction;
use App\Enums\MailAudience;
use App\Enums\MailTemplate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\SendFoundationMailRequest;
use App\Models\OutboundMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MailCenterController extends Controller
{
    /**
     * Show the email center.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', OutboundMessage::class);

        return view('portal.mail.index', [
            'user' => $request->user(),
            'messages' => OutboundMessage::query()->latest('sent_at')->limit(20)->get(),
            'templates' => MailTemplate::cases(),
            'audiences' => MailAudience::cases(),
        ]);
    }

    /**
     * Send a branded message.
     */
    public function store(SendFoundationMailRequest $request, SendFoundationMailAction $sendFoundationMail): RedirectResponse
    {
        $sender = $request->user();

        if ($sender === null) {
            abort(403);
        }

        try {
            $message = $sendFoundationMail->send($request, $sender);
        } catch (\InvalidArgumentException) {
            throw ValidationException::withMessages([
                'audience' => 'There is no one to receive this message.',
            ]);
        }

        return redirect()
            ->route('portal.mail.index')
            ->with('status', 'The message was sent to '.$message->recipient_count.' '.$this->people($message->recipient_count).'.');
    }

    private function people(int $count): string
    {
        return $count === 1 ? 'person' : 'people';
    }
}
