<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MusicCategory;
use App\Models\RadioStation;
use App\Services\MusicService;
use App\Services\RadioStationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MusicController extends Controller
{
    protected $musicService;
    protected $radioStationService;

    public function __construct(MusicService $musicService, RadioStationService $radioStationService)
    {
        $this->musicService = $musicService;
        $this->radioStationService = $radioStationService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->musicService->datatable();
        }
        $data['radioStations'] =  $this->radioStationService->getAll();
        return view('admin.music.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = MusicCategory::select('name', 'id')->get()->toArray();
        $radioStation = RadioStation::select('name', 'id')->get()->toArray();
        return view('admin.music.create', ['radioStation' => $radioStation, 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->musicService->validator($request->all());
        $validatedData['is_under_maintenence'] = checkRadioButton($validatedData['is_under_maintenence'] ?? null);

        try {
            $this->musicService->store($validatedData);
            return redirect()->route('music.index')->with([
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
        $data = $this->musicService->show($id);
        $categories = MusicCategory::select('name', 'id')->get();
        $radioStation = RadioStation::select('name', 'id')->get();
        return view('admin.music.edit', [
            'data' => $data,
            'categories' => $categories,
            'radioStation' => $radioStation,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $this->musicService->validator($request->all(), $id);
            $data = $this->musicService->update($id, $validatedData);
            // return $this->ResponseSuccess($data, 'Data updated successfully!');
            return redirect()->route('music.index')->with([
                'message' => 'Data update successfully.',
                'alert-type' => 'success',
            ]);
        } catch (ValidationException $exception) {
            return $this->ResponseError($exception->validator->errors());
        } catch (\Exception $e) {
            return $this->ResponseError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {

        try {
            $data = $this->musicService->delete($id);
            return redirect()->route('music.index')->with([
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


    protected function ResponseSuccess($data, $message = '')
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




