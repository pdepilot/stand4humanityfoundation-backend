<?php

namespace App\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FrontendActivityRecorded implements ShouldDispatchAfterCommit
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $type,
        public string $title,
        public string $body,
        public string $url,
        public string $reference,
    ) {}
}
