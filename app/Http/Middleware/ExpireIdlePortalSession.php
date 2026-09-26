<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ExpireIdlePortalSession
{
    public const IDLE_SECONDS = 15 * 60;

    private const ACTIVITY_KEY = 'portal_last_activity_at';

    /**
     * Sign out of the portal after 15 minutes without a user action.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() === null) {
            return $next($request);
        }

        $lastActivity = $request->session()->get(self::ACTIVITY_KEY);

        if (is_int($lastActivity) && (now()->getTimestamp() - $lastActivity) >= self::IDLE_SECONDS) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('portal.login')
                ->withErrors(['email' => 'You were signed out after 15 minutes of inactivity.']);
        }

        if (! $request->routeIs('portal.notifications.index')) {
            $request->session()->put(self::ACTIVITY_KEY, now()->getTimestamp());
        }

        return $next($request);
    }
}
