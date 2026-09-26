<?php

namespace App\Http\Requests\Portal;

use App\Http\Requests\StoreDonationRequest as PublicStoreDonationRequest;
use App\Models\Donation;

class StoreDonationRequest extends PublicStoreDonationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Donation::class) ?? false;
    }
}
