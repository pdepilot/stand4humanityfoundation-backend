<?php

namespace App\Notifications;

use App\Events\FrontendActivityRecorded;
use Illuminate\Notifications\Notification;

class FrontendActivityNotification extends Notification
{
    public function __construct(public FrontendActivityRecorded $activity) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array{type: string, title: string, body: string, url: string, reference: string}
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->activity->type,
            'title' => $this->activity->title,
            'body' => $this->activity->body,
            'url' => $this->activity->url,
            'reference' => $this->activity->reference,
        ];
    }
}
