<?php

namespace App\Http\Controllers\Portal;

use App\Enums\TestimonyStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\FoundationEvent;
use App\Models\FoundationMember;
use App\Models\GalleryItem;
use App\Models\IncidentReport;
use App\Models\NewsArticle;
use App\Models\NewsletterSubscriber;
use App\Models\SiteProfile;
use App\Models\TeamMember;
use App\Models\Testimony;
use App\Models\User;
use App\Models\VolunteerApplication;
use App\Support\DonationProgress;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the staff portal dashboard.
     */
    public function __invoke(Request $request): View
    {
        return view('portal.dashboard', [
            'user' => $request->user(),
            'staffCount' => User::query()->count(),
            'donorCount' => Donation::query()->count(),
            'caseCount' => IncidentReport::query()->count(),
            'contactCount' => ContactMessage::query()->count(),
            'volunteerCount' => VolunteerApplication::query()->count(),
            'newsCount' => NewsArticle::query()->count(),
            'eventCount' => FoundationEvent::query()->count(),
            'memberCount' => FoundationMember::query()->count(),
            'teamCount' => TeamMember::query()->count(),
            'galleryCount' => GalleryItem::query()->count(),
            'testimonyCount' => Testimony::query()->where('status', TestimonyStatus::Pending)->count(),
            'subscriberCount' => NewsletterSubscriber::query()->count(),
            'donationProgress' => DonationProgress::current(),
            'siteProfile' => SiteProfile::current(),
        ]);
    }
}
