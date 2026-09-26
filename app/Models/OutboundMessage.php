<?php

namespace App\Models;

use App\Enums\MailAudience;
use App\Enums\MailTemplate;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'sent_by',
    'template',
    'audience',
    'recipient_email',
    'subject',
    'body',
    'attachment_names',
    'recipient_count',
    'sent_at',
])]
class OutboundMessage extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'template' => MailTemplate::class,
            'audience' => MailAudience::class,
            'attachment_names' => 'array',
            'recipient_count' => 'integer',
            'sent_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
