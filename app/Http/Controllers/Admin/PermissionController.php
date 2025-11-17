<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index()
    {
        return view('admin.permissions.browse');
    }

    public function create()
    {
        $permission = new Permission();
        return view('admin.permissions.edit-add', compact('permission'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:191', 'unique:permissions,name'],
                'display_name' => ['required', 'string', 'max:255'],
                'group_name' => ['required', 'string', 'max:255'],
            ]);

            $this->permissionService->createPermission($validatedData);

            return redirect()
                ->back()
                ->with([
                    'message' => 'Permission created successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to create permission. Please try again.'])
                ->withInput();
        }
    }

    public function show(Permission $permission)
    {
        return view('admin.permissions.read', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit-add', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        try {
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:191', 'unique:permissions,name,' . $permission->id],
                'display_name' => ['required', 'string', 'max:255'],
                'group_name' => ['required', 'string', 'max:255'],
            ]);

            $this->permissionService->updatePermission($permission, $validatedData);

            return redirect()
                ->back()
                ->with([
                    'message' => 'Permission updated successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to update permission. Please try again.'])
                ->withInput();
        }
    }

    public function destroy(Permission $permission)
    {
        try {
            $this->permissionService->deletePermission($permission);

            return redirect()
                ->back()
                ->with([
                    'message' => 'Permission deleted successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to delete permission. Please try again.']);
        }
    }

    public function getDatatable(Request $request)
    {
        return $this->permissionService->getListDataForDatatable($request);
    }
}
