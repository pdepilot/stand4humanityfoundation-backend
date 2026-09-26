<?php

namespace App\Http\Requests\Portal;

use App\Http\Requests\StoreIncidentReportRequest as PublicStoreIncidentReportRequest;
use App\Models\IncidentReport;

class StoreIncidentReportRequest extends PublicStoreIncidentReportRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', IncidentReport::class) ?? false;
    }
}
