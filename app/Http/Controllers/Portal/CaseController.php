<?php

namespace App\Http\Controllers\Portal;

use App\Actions\CreateIncidentReportAction;
use App\Enums\IncidentSource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreIncidentReportRequest;
use App\Http\Requests\Portal\UpdateIncidentReportStatusRequest;
use App\Models\IncidentReport;
use App\Models\IncidentReportFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CaseController extends Controller
{
    /**
     * Display the command-desk case ledger.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', IncidentReport::class);

        $reports = IncidentReport::query()
            ->withCount('files')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('portal.cases.index', [
            'user' => $request->user(),
            'reports' => $reports,
            'caseCount' => $reports->total(),
        ]);
    }

    /**
     * Show the form for recording a desk case.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', IncidentReport::class);

        return view('portal.cases.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Record a case entered by an administrator.
     */
    public function store(StoreIncidentReportRequest $request, CreateIncidentReportAction $createIncidentReport): RedirectResponse
    {
        $report = $createIncidentReport->handle(
            $request->reportDetails(),
            IncidentSource::Desk,
            $request->evidenceFiles(),
        );

        return redirect()
            ->route('portal.cases.show', $report)
            ->with('status', 'The case is on the desk.');
    }

    /**
     * Display a case record.
     */
    public function show(Request $request, IncidentReport $incidentReport): View
    {
        Gate::authorize('view', $incidentReport);

        $incidentReport->load('files');

        return view('portal.cases.show', [
            'user' => $request->user(),
            'incidentReport' => $incidentReport,
        ]);
    }

    /**
     * Update the case status.
     */
    public function update(UpdateIncidentReportStatusRequest $request, IncidentReport $incidentReport): RedirectResponse
    {
        $incidentReport->update([
            'status' => $request->status(),
        ]);

        return redirect()
            ->route('portal.cases.show', $incidentReport)
            ->with('status', 'The case status was updated.');
    }

    /**
     * Stream a private evidence file to an administrator.
     */
    public function download(IncidentReport $incidentReport, IncidentReportFile $incidentReportFile): StreamedResponse
    {
        Gate::authorize('view', $incidentReport);

        if ((int) $incidentReportFile->incident_report_id !== (int) $incidentReport->id) {
            abort(404);
        }

        if (! Storage::disk($incidentReportFile->disk)->exists($incidentReportFile->path)) {
            abort(404);
        }

        return Storage::disk($incidentReportFile->disk)->download(
            $incidentReportFile->path,
            $incidentReportFile->original_name,
        );
    }

    /**
     * Remove a case and its private evidence.
     */
    public function destroy(IncidentReport $incidentReport): RedirectResponse
    {
        Gate::authorize('delete', $incidentReport);

        $incidentReport->load('files');

        foreach ($incidentReport->files as $file) {
            Storage::disk($file->disk)->delete($file->path);
        }

        Storage::disk('local')->deleteDirectory('incident-reports/'.$incidentReport->id);

        $incidentReport->delete();

        return redirect()
            ->route('portal.cases.index')
            ->with('status', 'The case was removed from the desk.');
    }
}
