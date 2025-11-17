<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\RadioStation;
use App\Services\SliderService;
use Carbon\Carbon;
use Illuminate\Http\Request;


class SliderController extends Controller
{
    protected $sliderService;

    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->sliderService->datatable();
        }
        $radioStations = RadioStation::select('name', 'id')->get();
        return view('admin.slider.index', compact('radioStations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $radioStation = RadioStation::select('name', 'id')->get();
        return view('admin.slider.create', compact('radioStation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->sliderService->validator($request->all());
        try {
            $this->sliderService->store($validatedData);
            return redirect()->route('slider.index')->with([
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
        $data = $this->sliderService->show($id);
        $radioStation = RadioStation::select('name', 'id')->get();
        return view('admin.slider.edit', compact('data', 'radioStation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $this->sliderService->validator($request->all(), $id);
        try {
            $data = $this->sliderService->update($id, $validatedData);
            return redirect()->route('slider.index')->with([
                'message' => 'Data update successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {

        try {
            $data = $this->sliderService->delete($id);
            return redirect()->route('slider.index')->with([
                'message' => 'Data delete successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

    }
}




