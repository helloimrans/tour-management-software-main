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

    public function approve($id)
    {
        if (!auth()->user()->hasPermission('general-user-update')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        try {
            $request = request();
            $request->validate([
                'roles' => ['required', 'array', 'min:1'],
                'roles.*' => ['required', 'integer', 'exists:roles,id'],
            ], [
                'roles.required' => 'Please select at least one role.',
                'roles.min' => 'Please select at least one role.',
            ]);

            $user = \App\Models\User::findOrFail($id);
            
            if ($user->status == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already approved.',
                ], 400);
            }

            // Approve user and assign roles
            $user->status = 1;
            $user->updated_by = auth()->id();
            $user->save();
            
            $roles = $request->input('roles', []);
            $user->syncRoles($roles);

            return response()->json([
                'success' => true,
                'message' => 'Member approved successfully with role assignment.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve member: ' . $e->getMessage(),
            ], 500);
        }
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
