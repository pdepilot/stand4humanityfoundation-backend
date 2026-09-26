<?php

namespace App\View\Composers;

use App\Support\DonationProgress;
use Illuminate\View\View;

class DonationProgressComposer
{
    /**
     * Share the live donation goal with public pages.
     */
    public function compose(View $view): void
    {
        $view->with('donationProgress', DonationProgress::current());
    }
}
