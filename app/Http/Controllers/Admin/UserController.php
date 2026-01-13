<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->userService->datatable();
        }

        return view('admin.user.index');
    }

    public function assignRole($id)
    {
        if (!auth()->user()->hasPermission('general-user-update')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        try {
            $roles = request('roles', []);
            $user = \App\Models\User::findOrFail($id);
            $user->syncRoles($roles);

            return response()->json([
                'success' => true,
                'message' => 'Role assigned successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign role.',
            ], 500);
        }
    }
}
