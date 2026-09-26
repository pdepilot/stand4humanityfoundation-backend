<?php

namespace App\Http\Controllers\Portal;

use App\Actions\StoreTestimonyPhotoAction;
use App\Enums\TestimonyStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreDeskTestimonyRequest;
use App\Http\Requests\Portal\UpdateTestimonyStatusRequest;
use App\Models\Testimony;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TestimonyController extends Controller
{
    /**
     * Display testimonies waiting for review and those already decided.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Testimony::class);

        $testimonies = Testimony::query()
            ->orderByRaw('case when status = ? then 0 else 1 end', [TestimonyStatus::Pending->value])
            ->orderByDesc('id')
            ->paginate(20);

        return view('portal.testimonies.index', [
            'user' => $request->user(),
            'testimonies' => $testimonies,
            'pendingCount' => Testimony::query()->where('status', TestimonyStatus::Pending)->count(),
        ]);
    }

    /**
     * Show the form for publishing a testimony from the desk.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', Testimony::class);

        return view('portal.testimonies.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Publish a testimony written at the desk.
     */
    public function store(StoreDeskTestimonyRequest $request, StoreTestimonyPhotoAction $storePhoto): RedirectResponse
    {
        $details = $request->testimonyDetails();
        $details['status'] = TestimonyStatus::Approved;
        $details['reviewed_at'] = now();
        $details['reviewed_by'] = $request->user()?->id;
        $photo = $request->uploadedPhoto();

        if ($photo !== null) {
            $details['photo'] = $storePhoto->store($photo);
        }

        $testimony = Testimony::query()->create($details);

        return redirect()
            ->route('portal.testimonies.show', $testimony)
            ->with('status', 'The testimony was published.');
    }

    /**
     * Display a testimony for review.
     */
    public function show(Request $request, Testimony $testimony): View
    {
        Gate::authorize('view', $testimony);

        return view('portal.testimonies.show', [
            'user' => $request->user(),
            'testimony' => $testimony,
        ]);
    }

    /**
     * Approve or decline a testimony.
     */
    public function update(UpdateTestimonyStatusRequest $request, Testimony $testimony): RedirectResponse
    {
        $status = $request->status();

        $testimony->update([
            'status' => $status,
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()?->id,
        ]);

        $message = match ($status) {
            TestimonyStatus::Approved => 'The testimony was approved and is on the homepage.',
            TestimonyStatus::Declined => 'The testimony was declined.',
            TestimonyStatus::Pending => 'The testimony is waiting for review.',
        };

        return redirect()
            ->route('portal.testimonies.show', $testimony)
            ->with('status', $message);
    }

    /**
     * Remove a testimony from the desk and the homepage.
     */
    public function destroy(Testimony $testimony, StoreTestimonyPhotoAction $storePhoto): RedirectResponse
    {
        Gate::authorize('delete', $testimony);

        $storePhoto->delete($testimony->photo);
        $testimony->delete();

        return redirect()
            ->route('portal.testimonies.index')
            ->with('status', 'The testimony was removed.');
    }
}
