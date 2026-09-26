<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePortalAccountIsActive
{
    /**
     * Log out a suspended account before the portal renders.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user !== null && $user->isSuspended()) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('portal.login')
                ->withErrors(['email' => 'This account is suspended.']);
        }

        return $next($request);
    }
}
