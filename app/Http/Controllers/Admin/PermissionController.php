<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use App\Models\Permission;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    const VIEW_PATH = 'admin.permissions.';

    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index(): View
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    public function create(): View
    {
        $permission = new Permission();
        return \view(self::VIEW_PATH . 'edit-add', compact('permission'));
    }

    public function store(Request $request): RedirectResponse
    {
        $postData = $request->only(array_keys($this->permissionService->validationRules()));

        $this->permissionService->validator($postData)->validate();

        try {
            $this->permissionService->createPermission($postData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => "Something went wrong!",
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => "Successfully created",
            'alert-type' => 'success'
        ]);
    }

    public function show(Permission $permission): View
    {
        return \view(self::VIEW_PATH . 'read', compact('permission'));
    }

    public function edit(Permission $permission): View
    {
        return \view(self::VIEW_PATH . 'edit-add', compact('permission'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $postData = $request->only(array_keys($this->permissionService->validationRules()));

        $this->permissionService->validator($postData, $permission->id)->validate();

        try {
            $this->permissionService->updatePermission($permission, $postData);
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

    public function destroy(Permission $permission): RedirectResponse
    {
        try {
            $this->permissionService->deletePermission($permission);
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
        return $this->permissionService->getListDataForDatatable($request);
    }
}
