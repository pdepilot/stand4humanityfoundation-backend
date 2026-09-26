<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\UpdateDonationGoalRequest;
use App\Models\DonationSetting;
use Illuminate\Http\RedirectResponse;

class DonationGoalController extends Controller
{
    /**
     * Set the public donation goal from the command desk.
     */
    public function update(UpdateDonationGoalRequest $request): RedirectResponse
    {
        DonationSetting::current()->update($request->goalAmounts());

        return redirect()
            ->route('portal.dashboard')
            ->with('status', 'The donation goals were updated.');
    }
}
