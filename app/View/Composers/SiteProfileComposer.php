<?php

namespace App\View\Composers;

use App\Models\SiteProfile;
use Illuminate\View\View;

class SiteProfileComposer
{
    /**
     * Share the public office details with the site and its mail.
     */
    public function compose(View $view): void
    {
        $view->with('siteProfile', SiteProfile::current());
    }
}
