<?php

namespace App\Http\Controllers\Portal;

use App\Actions\ExportAuditReportAction;
use App\Enums\AuditReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\ExportAuditReportRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditReportController extends Controller
{
    /**
     * Show the audit export desk.
     */
    public function index(Request $request): View
    {
        abort_unless($request->user()?->isAdministrator(), 403);

        return view('portal.audit.index', [
            'user' => $request->user(),
            'reports' => AuditReport::cases(),
        ]);
    }

    /**
     * Download the chosen ledger as a CSV file.
     */
    public function store(ExportAuditReportRequest $request, ExportAuditReportAction $exportAuditReport): StreamedResponse
    {
        return $exportAuditReport->download(
            $request->report(),
            $request->fromDate(),
            $request->toDate(),
        );
    }
}
