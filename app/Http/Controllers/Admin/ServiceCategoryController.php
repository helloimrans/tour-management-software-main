<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RadioStation;
use App\Services\ServiceCategoryService;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    protected $serviceCategoryService;

    public function __construct(ServiceCategoryService $serviceCategoryService)
    {
        $this->serviceCategoryService = $serviceCategoryService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->serviceCategoryService->datatable();
        }
        return view('admin.service-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $radioStations = RadioStation::select('id', 'name')->get();
        return view('admin.service-category.create', compact('radioStations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->serviceCategoryService->validator($request->all());
        try {
            $this->serviceCategoryService->store($validatedData);
            return redirect()->route('serviceCategory.index')->with([
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
        $data = $this->serviceCategoryService->show($id);
        $radioStations = RadioStation::select('id', 'name')->get();
        return view('admin.service-category.edit', compact('data', 'radioStations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $this->serviceCategoryService->validator($request->all(), $id);
        try {
            $data = $this->serviceCategoryService->update($id, $validatedData);
            return redirect()->route('serviceCategory.index')->with([
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
            $data = $this->serviceCategoryService->delete($id);
            return redirect()->route('serviceCategory.index')->with([
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
