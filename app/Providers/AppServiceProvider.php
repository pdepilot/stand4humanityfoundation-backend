<?php

namespace App\Providers;

use App\View\Composers\DonationProgressComposer;
use App\View\Composers\SiteProfileComposer;
use App\View\Composers\TeamRosterComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('newsletter', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        View::composer([
            'home',
            'site.donate',
            'site.donations',
        ], DonationProgressComposer::class);

        View::composer([
            'home',
            'site.about',
            'site.volunteer',
        ], TeamRosterComposer::class);

        View::composer([
            'home',
            'site.*',
            'mail.*',
        ], SiteProfileComposer::class);
    }
}
