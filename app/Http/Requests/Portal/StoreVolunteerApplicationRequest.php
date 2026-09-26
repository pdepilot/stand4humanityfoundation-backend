<?php

namespace App\Http\Requests\Portal;

use App\Http\Requests\StoreVolunteerApplicationRequest as PublicStoreVolunteerApplicationRequest;
use App\Models\VolunteerApplication;

class StoreVolunteerApplicationRequest extends PublicStoreVolunteerApplicationRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', VolunteerApplication::class) ?? false;
    }
}
