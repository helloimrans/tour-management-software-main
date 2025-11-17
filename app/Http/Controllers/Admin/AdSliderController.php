<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RadioStation;
use App\Services\AdSliderService;
use App\Services\RadioStationService;
use Illuminate\Http\Request;

class AdSliderController extends Controller
{
    protected $adSliderService;
    protected $radioStationService;

    public function __construct(AdSliderService $adSliderService, RadioStationService $radioStationService)
    {
        $this->adSliderService = $adSliderService;
        $this->radioStationService = $radioStationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->adSliderService->datatable();
        }

        return view('admin.ad-sliders.index', [
            'radioStations' => $this->radioStationService->getAll()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ad-sliders.create', [
            'radioStations' => $this->radioStationService->getAll()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->adSliderService->validator($request->all());
            $this->adSliderService->store($validatedData);

            return redirect()->route('adSlider.index')->with([
                'message' => 'Data stored successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
   
    public function edit(string $id)
    {
        $data = $this->adSliderService->show($id);
        $radioStation = RadioStation::select('name', 'id')->get();
        return view('admin.ad-sliders.edit', compact('data', 'radioStation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $this->adSliderService->validator($request->all(), $id);
            $this->adSliderService->update($id, $validatedData); // Ensure update is performed

            return redirect()->route('adSlider.index')->with([
                'message' => 'Data updated successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->adSliderService->delete($id);

            return redirect()->route('adSlider.index')->with([
                'message' => 'Data deleted successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
