<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RadioStation;
use App\Models\ServiceCategory;
use App\Services\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->serviceService->datatable();
        }
        return view('admin.service.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $radioStations = RadioStation::select('id', 'name')->get();
        $categories = ServiceCategory::select('id', 'name')->get();
        return view('admin.service.create', compact('radioStations', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->serviceService->validator($request->all());
        try {
            $this->serviceService->store($validatedData);
            return redirect()->route('service.index')->with([
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
        $radioStations = RadioStation::select('id', 'name')->get();
        $categories = ServiceCategory::select('id', 'name')->get();
        $data = $this->serviceService->show($id);
        return view('admin.service.edit', compact('data', 'radioStations', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $this->serviceService->validator($request->all(), $id);
            $data = $this->serviceService->update($id, $validatedData);
            return redirect()->route('service.index')->with([
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
            $data = $this->serviceService->delete($id);
            return redirect()->route('service.index')->with([
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


    protected function ResponseSuccess($data = null, $message = '')
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    protected function ResponseError($error)
    {
        return response()->json([
            'success' => false,
            'message' => $error
        ]);
    }
}
