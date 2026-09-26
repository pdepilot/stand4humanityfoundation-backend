<?php

namespace App\Http\Controllers\Portal;

use App\Actions\StoreSiteGalleryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\UpdateSiteProfileRequest;
use App\Models\SiteProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SiteProfileController extends Controller
{
    /**
     * Show the public site details an administrator can change.
     */
    public function edit(Request $request): View
    {
        $profile = SiteProfile::current();

        Gate::authorize('view', $profile);

        return view('portal.site.edit', [
            'user' => $request->user(),
            'profile' => $profile,
        ]);
    }

    /**
     * Save the public office, social links, hours, and footer gallery.
     */
    public function update(
        UpdateSiteProfileRequest $request,
        StoreSiteGalleryAction $storeGallery,
    ): RedirectResponse {
        $profile = SiteProfile::current();
        $details = $request->profileDetails();

        foreach (range(1, 6) as $slot) {
            $upload = $request->galleryFile($slot);

            if ($upload === null) {
                continue;
            }

            $column = 'gallery_'.$slot;
            $previous = $profile->{$column};
            $details[$column] = $storeGallery->store($upload);
            $storeGallery->delete($previous);
        }

        $profile->update($details);

        return redirect()
            ->route('portal.site.edit')
            ->with('status', 'The public site details were updated.');
    }
}
