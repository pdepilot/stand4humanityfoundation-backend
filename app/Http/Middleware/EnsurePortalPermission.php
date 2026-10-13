<?php

namespace App\Http\Middleware;

use App\Enums\PortalPermission;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePortalPermission
{
    /**
     * Refuse a portal page when the signed-in administrator's role does not include it.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $permission = PortalPermission::fromRouteName($request->route()?->getName());
        $user = $request->user();

        if ($permission === null || ! $user instanceof User || $user->canOpenPortal($permission)) {
            return $next($request);
        }

        abort(403, PortalPermission::DENIED_MESSAGE);
    }
}
