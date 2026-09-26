<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\UpdateLoginDetailsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Show the administrator login-details form.
     */
    public function edit(Request $request): View
    {
        Gate::authorize('updateLoginDetails', $request->user());

        return view('portal.settings.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the administrator email and, when provided, password.
     */
    public function update(UpdateLoginDetailsRequest $request): RedirectResponse
    {
        $details = $request->loginDetails();
        $request->user()->update($details);

        if (array_key_exists('password', $details)) {
            Auth::logoutOtherDevices($details['password']);
            $request->session()->regenerate();
        }

        return redirect()
            ->route('portal.settings.edit')
            ->with('status', 'Login details were updated.');
    }
}
