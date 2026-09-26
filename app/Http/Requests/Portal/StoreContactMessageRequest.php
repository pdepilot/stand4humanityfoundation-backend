<?php

namespace App\Http\Requests\Portal;

use App\Http\Requests\StoreContactMessageRequest as PublicStoreContactMessageRequest;
use App\Models\ContactMessage;

class StoreContactMessageRequest extends PublicStoreContactMessageRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', ContactMessage::class) ?? false;
    }
}
