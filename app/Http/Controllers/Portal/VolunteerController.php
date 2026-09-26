<?php

namespace App\Http\Controllers\Portal;

use App\Actions\CreateVolunteerApplicationAction;
use App\Actions\StoreVolunteerPhotoAction;
use App\Enums\VolunteerSource;
use App\Enums\VolunteerStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreVolunteerApplicationRequest;
use App\Http\Requests\Portal\UpdateVolunteerApplicationStatusRequest;
use App\Models\VolunteerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class VolunteerController extends Controller
{
    /**
     * Display the command-desk volunteer ledger.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', VolunteerApplication::class);

        $applications = VolunteerApplication::query()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('portal.volunteers.index', [
            'user' => $request->user(),
            'applications' => $applications,
            'volunteerCount' => $applications->total(),
            'pendingCount' => VolunteerApplication::query()
                ->whereIn('status', [VolunteerStatus::Pending, VolunteerStatus::Reviewing])
                ->count(),
        ]);
    }

    /**
     * Show the form for recording a desk volunteer.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', VolunteerApplication::class);

        return view('portal.volunteers.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Record a volunteer entered by an administrator.
     */
    public function store(
        StoreVolunteerApplicationRequest $request,
        CreateVolunteerApplicationAction $createVolunteerApplication,
        StoreVolunteerPhotoAction $storeVolunteerPhoto,
    ): RedirectResponse {
        $details = $request->applicationDetails();
        $details['photo'] = $storeVolunteerPhoto->store($request->uploadedPhoto());

        $application = $createVolunteerApplication->handle(
            $details,
            VolunteerSource::Desk,
        );

        return redirect()
            ->route('portal.volunteers.show', $application)
            ->with('status', 'The volunteer application is on the desk.');
    }

    /**
     * Display a volunteer application for vetting.
     */
    public function show(Request $request, VolunteerApplication $volunteerApplication): View
    {
        Gate::authorize('view', $volunteerApplication);

        return view('portal.volunteers.show', [
            'user' => $request->user(),
            'volunteerApplication' => $volunteerApplication,
        ]);
    }

    /**
     * Update the vetting status.
     */
    public function update(
        UpdateVolunteerApplicationStatusRequest $request,
        VolunteerApplication $volunteerApplication,
    ): RedirectResponse {
        $status = $request->status();

        $volunteerApplication->update([
            'status' => $status,
            'admin_notes' => $request->adminNotes(),
            'reviewed_by' => $status->isTerminal() ? $request->user()?->id : $volunteerApplication->reviewed_by,
            'reviewed_at' => $status->isTerminal() ? now() : $volunteerApplication->reviewed_at,
        ]);

        return redirect()
            ->route('portal.volunteers.show', $volunteerApplication)
            ->with('status', 'The vetting status was updated.');
    }

    /**
     * Remove a volunteer application.
     */
    public function destroy(
        VolunteerApplication $volunteerApplication,
        StoreVolunteerPhotoAction $storeVolunteerPhoto,
    ): RedirectResponse {
        Gate::authorize('delete', $volunteerApplication);

        $photoPath = $volunteerApplication->photo;
        $volunteerApplication->delete();
        $storeVolunteerPhoto->delete($photoPath);

        return redirect()
            ->route('portal.volunteers.index')
            ->with('status', 'The volunteer application was removed from the desk.');
    }
}
