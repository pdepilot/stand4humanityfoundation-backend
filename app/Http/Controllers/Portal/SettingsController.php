<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\UpdateLoginDetailsRequest;
use App\Http\Requests\Portal\UpdateOfficePhoneRequest;
use App\Models\SiteProfile;
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
            'profile' => SiteProfile::current(),
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

    /**
     * Save the phone number shown on the public site.
     */
    public function updatePhone(UpdateOfficePhoneRequest $request): RedirectResponse
    {
        SiteProfile::current()->update([
            'phone' => $request->validated('phone'),
        ]);

        return redirect()
            ->route('portal.settings.edit')
            ->with('phone_status', 'The public phone number was updated.');
    }
}
