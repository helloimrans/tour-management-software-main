<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    const VIEW_PATH = 'admin.roles.';

    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(): View
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    public function create(): View
    {
        $role = new Role();
        return \view(self::VIEW_PATH . 'edit-add', compact('role'));
    }

    public function store(Request $request): RedirectResponse
    {
        $input = $request->all();

        $trimmedInput = array_map('trim', $input);

        $validatedData = $this->roleService->validator($trimmedInput)->validate();

        try {
            $this->roleService->createRole($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => "Something went wrong!",
                'alert-type' => 'error'
            ]);
        }

        return redirect()->route('roles.index')->with([
            'message' => "Successfully created",
            'alert-type' => 'success'
        ]);
    }

    public function show(Role $role): View
    {
        return \view(self::VIEW_PATH . 'read', compact('role'));
    }

    public function edit(Role $role): View
    {
        return \view(self::VIEW_PATH . 'edit-add', compact('role'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validatedData = $this->roleService->validator($request->all(), $role->id)->validate();

        try {
            $this->roleService->updateRole($role, $validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => "Something went wrong!",
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => "Successfully updated",
            'alert-type' => 'success'
        ]);
    }

    public function destroy(Role $role): RedirectResponse
    {
        try {
            $this->roleService->deleteRole($role);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => "Something went wrong!",
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => "Successfully deleted",
            'alert-type' => 'success'
        ]);
    }

    public function getDatatable(Request $request): JsonResponse
    {
        return $this->roleService->getListDataForDatatable($request);
    }

    public function rolePermissionIndex(Role $role): View
    {
        $permissionsGroupByTable = Permission::all()->groupBy('group_name');

        return \view(self::VIEW_PATH . 'permissions', compact('role', 'permissionsGroupByTable'));
    }

    public function rolePermissionSync(Request $request, Role $role): RedirectResponse
    {
        $permissions = $request->input('permissions', []);

        try {
            $this->roleService->syncRolePermission($role, $permissions);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => "Something went wrong!",
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => "Successfully updated",
            'alert-type' => 'success'
        ]);
    }
}
