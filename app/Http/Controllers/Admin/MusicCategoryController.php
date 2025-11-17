<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MusicCategory;
use Illuminate\Http\Request;
use App\Services\MusicCategoryService;
use App\Services\RadioStationService;
use Illuminate\Validation\ValidationException;


class MusicCategoryController extends Controller
{
    protected $musicCategoryService;
    protected $radioStationService;

    public function __construct(MusicCategoryService $musicCategoryService, RadioStationService $radioStationService)
    {
        $this->musicCategoryService = $musicCategoryService;
        $this->radioStationService = $radioStationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            return $this->musicCategoryService->datatable($request);
        }
        $data['radioStations'] =  $this->radioStationService->getAll();
        return view('admin.music-category.index', $data);
    }

    public function create()
    {
        $data['radioStations'] =  $this->radioStationService->getAll();
        return view('admin.music-category.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->musicCategoryService->validator($request->all());
        $validatedData['is_under_maintenence'] = checkRadioButton($validatedData['is_under_maintenence'] ?? null);
        $this->musicCategoryService->store($validatedData);
        return redirect()->route('music.category.index')->with([
            'message' => 'Data stored successfully.',
            'alert-type' => 'success',
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function edit(string $id)
    {
        $data['data'] = $this->musicCategoryService->show($id);
        $data['radioStations'] =  $this->radioStationService->getAll();
        return view('admin.music-category.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validatedData = $this->musicCategoryService->validator($request->all(), $id);
        $data = $this->musicCategoryService->update($id, $validatedData);
        return redirect()->route('music.category.index')->with([
            'message' => 'Data updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {

        try {
            $data = $this->musicCategoryService->delete($id);
            return redirect()->route('music.category.index')->with([
                'message' => 'Data Delete successfully.',
                'alert-type' => 'success',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
