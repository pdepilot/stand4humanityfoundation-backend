<?php

namespace App\Http\Controllers\Portal;

use App\Actions\GuardPortalLoginAction;
use App\Http\Controllers\Controller;
use App\Models\PortalLoginBan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LoginBanController extends Controller
{
    /**
     * List devices that are currently blocked from the portal.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', PortalLoginBan::class);

        $bans = PortalLoginBan::query()
            ->where(function ($query): void {
                $query->where('stage', '>=', 3)
                    ->orWhere('banned_until', '>', now());
            })
            ->orderByDesc('banned_until')
            ->orderByDesc('id')
            ->get();

        return view('portal.login-bans.index', [
            'user' => $request->user(),
            'bans' => $bans,
        ]);
    }

    /**
     * Lift a device block.
     */
    public function destroy(PortalLoginBan $portalLoginBan, GuardPortalLoginAction $guardPortalLogin): RedirectResponse
    {
        Gate::authorize('delete', $portalLoginBan);

        $address = $portalLoginBan->ip_address;
        $guardPortalLogin->lift($portalLoginBan);

        return redirect()
            ->route('portal.login-bans.index')
            ->with('status', $address.' can sign in again.');
    }
}
