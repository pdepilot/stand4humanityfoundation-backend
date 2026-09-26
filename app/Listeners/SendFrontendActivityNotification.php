<?php

namespace App\Listeners;

use App\Events\FrontendActivityRecorded;
use App\Models\User;
use App\Notifications\FrontendActivityNotification;
use Illuminate\Support\Facades\Notification;

class SendFrontendActivityNotification
{
    /**
     * Store a desk alert for every administrator.
     */
    public function handle(FrontendActivityRecorded $event): void
    {
        $administrators = User::query()->administrators()->get();

        if ($administrators->isEmpty()) {
            return;
        }

        Notification::send($administrators, new FrontendActivityNotification($event));
    }
}
