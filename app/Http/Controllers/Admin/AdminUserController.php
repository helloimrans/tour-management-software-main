<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\AdminUserService;
use App\Services\RadioStationService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AdminUserController extends Controller
{
    protected AdminUserService $adminUserService;
    protected RadioStationService $radioStationService;

    public function __construct(
        AdminUserService $adminUserService,
        RadioStationService $radioStationService
    ) {
        $this->adminUserService = $adminUserService;
        $this->radioStationService = $radioStationService;
    }

    public function index()
    {
        if (request()->ajax()) {
            return $this->adminUserService->datatable();
        }

        return view('admin.admin-user.index');
    }

    public function create()
    {
        $data = [
            'radioStations' => $this->radioStationService->getAll(),
            'roles' => Role::all(),
        ];

        return view('admin.admin-user.create', $data);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $this->adminUserService->validator($request->all());
            $this->adminUserService->store($validatedData);

            return redirect()
                ->route('admin.user.index')
                ->with([
                    'message' => 'Admin user created successfully.',
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
                ->withErrors(['error' => 'Failed to create admin user. Please try again.'])
                ->withInput();
        }
    }

    public function edit(string $id)
    {
        $data = [
            'data' => $this->adminUserService->show($id),
            'radioStations' => $this->radioStationService->getAll(),
            'roles' => Role::all(),
        ];

        $data['role_ids'] = $data['data']->roles()->pluck('id')->toArray();

        return view('admin.admin-user.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $this->adminUserService->validator($request->all(), $id);
            $this->adminUserService->update($id, $validatedData);

            return redirect()
                ->route('admin.user.index')
                ->with([
                    'message' => 'Admin user updated successfully.',
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
                ->withErrors(['error' => 'Failed to update admin user. Please try again.'])
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->adminUserService->delete($id);

            return redirect()
                ->route('admin.user.index')
                ->with([
                    'message' => 'Admin user deleted successfully.',
                    'alert-type' => 'success',
                ]);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to delete admin user. Please try again.']);
        }
    }
}
