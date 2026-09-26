<?php

namespace App\Http\Controllers;

use App\Actions\CreateIncidentReportAction;
use App\Enums\IncidentSource;
use App\Http\Requests\StoreIncidentReportRequest;
use App\Models\IncidentReport;
use Illuminate\Http\JsonResponse;

class IncidentReportController extends Controller
{
    /**
     * Record a case from the public Report Live form.
     */
    public function store(StoreIncidentReportRequest $request, CreateIncidentReportAction $createIncidentReport): JsonResponse
    {
        $report = $createIncidentReport->handle(
            $request->reportDetails(),
            IncidentSource::Public,
            $request->evidenceFiles(),
        );

        return response()->json([
            'ok' => true,
            'report' => $report->toPublicArray(),
        ], 201);
    }

    /**
     * Return the public tracking snapshot for a report reference.
     */
    public function show(IncidentReport $incidentReport): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'report' => $incidentReport->toPublicArray(),
        ]);
    }
}
