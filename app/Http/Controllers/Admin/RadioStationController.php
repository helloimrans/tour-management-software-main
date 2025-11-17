<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RadioStationService;
use Illuminate\Validation\ValidationException;

class RadioStationController extends Controller
{
    protected $radioStationService;

    public function __construct(RadioStationService $radioStationService)
    {
        $this->radioStationService = $radioStationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->radioStationService->datatable();
        }
        return view('admin.radio-station.index');
    }

    public function create()
    {
        return view('admin.radio-station.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->radioStationService->validator($request->all());
        $validatedData['is_under_maintenence'] = checkRadioButton($validatedData['is_under_maintenence'] ?? null);
        $this->radioStationService->store($validatedData);
        return redirect()->route('radio.stations.index')->with([
            'message' => 'Data stored successfully.',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $data['data'] = $this->radioStationService->show($id);
        return view('admin.radio-station.edit', $data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData['is_under_maintenence'] = checkRadioButton($validatedData['is_under_maintenence'] ?? null);
        $validatedData = $this->radioStationService->validator($request->all(), $id);

        $data = $this->radioStationService->update($id, $validatedData);
        return redirect()->route('radio.stations.index')->with([
            'message' => 'Data updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = $this->radioStationService->delete($id);
            return redirect()->back()->with([
                'message' => 'Data stored successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
