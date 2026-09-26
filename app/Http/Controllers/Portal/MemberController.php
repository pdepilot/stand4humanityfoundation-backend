<?php

namespace App\Http\Controllers\Portal;

use App\Actions\StoreFoundationMemberPhotoAction;
use App\Enums\MemberGender;
use App\Enums\MembershipStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreFoundationMemberRequest;
use App\Http\Requests\Portal\UpdateFoundationMemberRequest;
use App\Mail\MemberSuspended;
use App\Models\FoundationMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class MemberController extends Controller
{
    /**
     * Display the members ledger, optionally filtered by name or member number.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', FoundationMember::class);

        $search = trim($request->string('q')->toString());

        $members = FoundationMember::query()
            ->when($search !== '', fn ($query) => $query->lookup($search))
            ->orderBy('full_name')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return view('portal.members.index', [
            'user' => $request->user(),
            'members' => $members,
            'memberCount' => FoundationMember::query()->count(),
            'activeCount' => FoundationMember::query()->where('status', MembershipStatus::Active)->count(),
            'search' => $search,
        ]);
    }

    /**
     * Show the form for recording a member.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', FoundationMember::class);

        return view('portal.members.create', [
            'user' => $request->user(),
            'genders' => MemberGender::cases(),
            'statuses' => MembershipStatus::cases(),
        ]);
    }

    /**
     * Store a member from the desk.
     */
    public function store(StoreFoundationMemberRequest $request, StoreFoundationMemberPhotoAction $storePhoto): RedirectResponse
    {
        $details = $request->memberDetails();
        $details['photo'] = $storePhoto->store($request->uploadedPhoto());

        $member = DB::transaction(function () use ($details): FoundationMember {
            $details['member_number'] = $this->nextMemberNumber();

            return FoundationMember::query()->create($details);
        });

        return redirect()
            ->route('portal.members.show', $member)
            ->with('status', 'The member is on the roll.');
    }

    /**
     * Display a member record.
     */
    public function show(Request $request, FoundationMember $foundationMember): View
    {
        Gate::authorize('view', $foundationMember);

        return view('portal.members.show', [
            'user' => $request->user(),
            'member' => $foundationMember,
        ]);
    }

    /**
     * Show the form for editing a member.
     */
    public function edit(Request $request, FoundationMember $foundationMember): View
    {
        Gate::authorize('update', $foundationMember);

        return view('portal.members.edit', [
            'user' => $request->user(),
            'member' => $foundationMember,
            'genders' => MemberGender::cases(),
            'statuses' => MembershipStatus::cases(),
        ]);
    }

    /**
     * Update a member record.
     */
    public function update(
        UpdateFoundationMemberRequest $request,
        FoundationMember $foundationMember,
        StoreFoundationMemberPhotoAction $storePhoto,
    ): RedirectResponse {
        $details = $request->memberDetails();
        $uploadedPhoto = $request->uploadedPhoto();

        if ($uploadedPhoto !== null) {
            $previousPhoto = $foundationMember->photo;
            $details['photo'] = $storePhoto->store($uploadedPhoto);
            $storePhoto->delete($previousPhoto);
        }

        $wasSuspended = $foundationMember->status === MembershipStatus::Suspended;

        $foundationMember->update($details);

        if (! $wasSuspended && $foundationMember->status === MembershipStatus::Suspended) {
            $this->notifyDeskSuspension($foundationMember);
        }

        return redirect()
            ->route('portal.members.show', $foundationMember)
            ->with('status', 'The member record was updated.');
    }

    /**
     * Suspend a member from the desk, or restore one who is already suspended.
     */
    public function suspend(FoundationMember $foundationMember): RedirectResponse
    {
        Gate::authorize('suspend', $foundationMember);

        if ($foundationMember->status === MembershipStatus::Suspended) {
            $foundationMember->update(['status' => MembershipStatus::Active]);

            return redirect()
                ->route('portal.members.show', $foundationMember)
                ->with('status', $foundationMember->full_name.' was restored to the roll.');
        }

        $foundationMember->update(['status' => MembershipStatus::Suspended]);
        $this->notifyDeskSuspension($foundationMember);

        return redirect()
            ->route('portal.members.show', $foundationMember)
            ->with('status', $foundationMember->full_name.' was suspended.');
    }

    /**
     * Remove a member from the roll.
     */
    public function destroy(FoundationMember $foundationMember, StoreFoundationMemberPhotoAction $storePhoto): RedirectResponse
    {
        Gate::authorize('delete', $foundationMember);

        $name = $foundationMember->full_name;
        $storePhoto->delete($foundationMember->photo);
        $foundationMember->delete();

        return redirect()
            ->route('portal.members.index')
            ->with('status', $name.' was removed from the roll.');
    }

    /**
     * Issue the next roll number, locked so two desks cannot share it.
     */
    private function nextMemberNumber(): string
    {
        $latest = FoundationMember::query()
            ->lockForUpdate()
            ->orderByDesc('id')
            ->value('member_number');

        $sequence = 1;

        if (is_string($latest) && preg_match('/(\d+)$/', $latest, $matches) === 1) {
            $sequence = ((int) $matches[1]) + 1;
        }

        return 'SFH-'.str_pad((string) $sequence, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Tell a member the desk suspended them. A mail failure leaves the suspension in place.
     */
    private function notifyDeskSuspension(FoundationMember $member): void
    {
        if (! is_string($member->email) || trim($member->email) === '') {
            return;
        }

        try {
            Mail::to($member->email)->send(new MemberSuspended($member, forAbsence: false));
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
