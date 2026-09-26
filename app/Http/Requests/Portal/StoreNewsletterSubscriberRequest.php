<?php

namespace App\Http\Requests\Portal;

use App\Http\Requests\StoreNewsletterSubscriberRequest as PublicStoreNewsletterSubscriberRequest;
use App\Models\NewsletterSubscriber;

class StoreNewsletterSubscriberRequest extends PublicStoreNewsletterSubscriberRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', NewsletterSubscriber::class) ?? false;
    }
}
