<?php

namespace App\Models;

use Database\Factories\IncidentReportFileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'incident_report_id',
    'disk',
    'path',
    'original_name',
    'mime',
    'size',
])]
class IncidentReportFile extends Model
{
    /** @use HasFactory<IncidentReportFileFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<IncidentReport, $this>
     */
    public function incidentReport(): BelongsTo
    {
        return $this->belongsTo(IncidentReport::class);
    }
}
