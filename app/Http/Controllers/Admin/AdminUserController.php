<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RadioStation;
use App\Models\Role;
use App\Models\ServiceCategory;
use App\Services\AdminUserService;
use App\Services\MusicService;
use App\Services\RadioStationService;
use App\Services\ServiceService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminUserController extends Controller
{
    protected $adminUserService;
    protected $radioStationService;

    public function __construct(AdminUserService $adminUserService, RadioStationService $radioStationService)
    {
        $this->adminUserService = $adminUserService;
        $this->radioStationService = $radioStationService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->adminUserService->datatable();
        }
        return view('admin.admin-user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['radioStations'] =  $this->radioStationService->getAll();
        $data['roles'] =  Role::all();
        return view('admin.admin-user.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $this->adminUserService->validator($request->all());
        try {
            $this->adminUserService->store($validatedData);
            return redirect()->route('admin.user.index')->with([
                'message' => 'Data stored successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $data['data'] = $this->adminUserService->show($id);
        $data['radioStations'] =  $this->radioStationService->getAll();
        $data['roles'] = Role::all();
        $data['role_ids'] = $data['data']->roles()->pluck('id')->toArray();
        return view('admin.admin-user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $this->adminUserService->validator($request->all(), $id);
        try {
            $data = $this->adminUserService->update($id, $validatedData);
            return redirect()->route('admin.user.index')->with([
                'message' => 'Data stored successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = $this->adminUserService->delete($id);
            return redirect()->route('admin.user.index')->with([
                'message' => 'Data delete successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function checkRadioButton($value)
    {
        return $value === 'on' ? 1 : 0; // Adjust this as needed
    }


}
