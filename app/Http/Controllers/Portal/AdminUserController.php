<?php

namespace App\Http\Controllers\Portal;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\AssignAdminRoleRequest;
use App\Http\Requests\Portal\StoreAdminUserRequest;
use App\Models\AdminRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display administrators the super administrator can manage.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $administrators = User::query()
            ->with('adminRole')
            ->whereIn('role', [UserRole::SuperAdministrator, UserRole::Administrator])
            ->orderBy('name')
            ->get();

        return view('portal.admins.index', [
            'user' => $request->user(),
            'administrators' => $administrators,
            'roles' => AdminRole::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a new administrator.
     */
    public function store(StoreAdminUserRequest $request): RedirectResponse
    {
        User::query()->create($request->administratorDetails());

        return redirect()
            ->route('portal.admins.index')
            ->with('status', 'The administrator was added.');
    }

    /**
     * Assign or clear the role that limits an administrator's pages.
     */
    public function assignRole(AssignAdminRoleRequest $request, User $user): RedirectResponse
    {
        $user->update([
            'admin_role_id' => $request->validated('admin_role_id'),
        ]);

        return redirect()
            ->route('portal.admins.index')
            ->with('status', 'The administrator role was updated.');
    }

    /**
     * Suspend or restore an administrator.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('suspend', $user);

        $user->update([
            'suspended_at' => $user->isSuspended() ? null : now(),
        ]);

        return redirect()
            ->route('portal.admins.index')
            ->with('status', $user->isSuspended() ? 'The administrator was suspended.' : 'The administrator was restored.');
    }

    /**
     * Remove an administrator.
     */
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return redirect()
            ->route('portal.admins.index')
            ->with('status', 'The administrator was removed.');
    }
}
