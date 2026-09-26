<?php

namespace App\Http\Controllers\Portal;

use App\Actions\StoreTeamMemberPhotoAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreTeamMemberRequest;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TeamMemberController extends Controller
{
    /**
     * Display the public team roster.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', TeamMember::class);

        $teamMembers = TeamMember::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('portal.team.index', [
            'user' => $request->user(),
            'teamMembers' => $teamMembers,
        ]);
    }

    /**
     * Show the form for adding a team member.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', TeamMember::class);

        return view('portal.team.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Add a team member to the public roster.
     */
    public function store(StoreTeamMemberRequest $request, StoreTeamMemberPhotoAction $storePhoto): RedirectResponse
    {
        $details = $request->teamMemberDetails();
        $details['photo'] = $storePhoto->store($request->uploadedPhoto());
        $details['sort_order'] = ((int) TeamMember::query()->max('sort_order')) + 1;

        $teamMember = TeamMember::query()->create($details);

        return redirect()
            ->route('portal.team.show', $teamMember)
            ->with('status', $teamMember->name.' was added to the team.');
    }

    /**
     * Display a team member.
     */
    public function show(Request $request, TeamMember $teamMember): View
    {
        Gate::authorize('view', $teamMember);

        return view('portal.team.show', [
            'user' => $request->user(),
            'teamMember' => $teamMember,
        ]);
    }

    /**
     * Remove a team member from the public roster.
     */
    public function destroy(TeamMember $teamMember, StoreTeamMemberPhotoAction $storePhoto): RedirectResponse
    {
        Gate::authorize('delete', $teamMember);

        $name = $teamMember->name;
        $storePhoto->delete($teamMember->photo);
        $teamMember->delete();

        return redirect()
            ->route('portal.team.index')
            ->with('status', $name.' was removed from the team.');
    }
}
