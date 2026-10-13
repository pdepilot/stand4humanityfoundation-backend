<?php

namespace App\Http\Controllers\Portal;

use App\Enums\PortalPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreAdminRoleRequest;
use App\Http\Requests\Portal\UpdateAdminRoleRequest;
use App\Models\AdminRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdminRoleController extends Controller
{
    /**
     * Display administrator roles the super administrator can manage.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', AdminRole::class);

        return view('portal.roles.index', [
            'user' => $request->user(),
            'roles' => AdminRole::query()->withCount('administrators')->orderBy('name')->get(),
            'permissions' => PortalPermission::cases(),
        ]);
    }

    /**
     * Store a new administrator role.
     */
    public function store(StoreAdminRoleRequest $request): RedirectResponse
    {
        $role = AdminRole::query()->create($request->roleDetails());

        return redirect()
            ->route('portal.roles.index')
            ->with('status', $role->name.' was added.');
    }

    /**
     * Show the form for changing a role.
     */
    public function edit(Request $request, AdminRole $adminRole): View
    {
        Gate::authorize('update', $adminRole);

        return view('portal.roles.edit', [
            'user' => $request->user(),
            'role' => $adminRole,
            'permissions' => PortalPermission::cases(),
        ]);
    }

    /**
     * Save a role name and its pages.
     */
    public function update(UpdateAdminRoleRequest $request, AdminRole $adminRole): RedirectResponse
    {
        $adminRole->update($request->roleDetails());

        return redirect()
            ->route('portal.roles.index')
            ->with('status', 'The role was updated.');
    }

    /**
     * Remove a role that is not assigned to an administrator.
     */
    public function destroy(AdminRole $adminRole): RedirectResponse
    {
        Gate::authorize('delete', $adminRole);

        if ($adminRole->administrators()->exists()) {
            return redirect()
                ->route('portal.roles.index')
                ->withErrors(['role' => 'Move the administrators off this role before you remove it.']);
        }

        $name = $adminRole->name;
        $adminRole->delete();

        return redirect()
            ->route('portal.roles.index')
            ->with('status', $name.' was removed.');
    }
}
