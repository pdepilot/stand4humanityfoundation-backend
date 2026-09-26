<?php

namespace App\Http\Controllers\Portal;

use App\Actions\GuardPortalLoginAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\LoginRequest;
use App\Support\PortalLoginVisual;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private PortalLoginVisual $loginVisual) {}

    /**
     * Display the staff portal login form.
     */
    public function create(Request $request): Response
    {
        $visual = $this->loginVisual->current($request);

        return response()
            ->view('portal.login', [
                'loginVisual' => $visual,
            ])
            ->withCookie($this->loginVisual->cookie($visual['index']));
    }

    /**
     * Authenticate the incoming portal login request.
     */
    public function store(LoginRequest $request, GuardPortalLoginAction $guardPortalLogin): RedirectResponse
    {
        $request->authenticate($guardPortalLogin);

        $request->session()->regenerate();

        return redirect()->intended(route('portal.dashboard'));
    }

    /**
     * Destroy an authenticated portal session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $nextVisual = $this->loginVisual->nextCookie($request);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('portal.login', ['from' => 'logout'])
            ->withCookie($nextVisual);
    }
}
