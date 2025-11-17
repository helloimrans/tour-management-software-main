<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RadioStation;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceProvider;
use App\Services\ServiceProviderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ServiceProviderController extends Controller
{
    protected $serviceProviderService;

    public function __construct(ServiceProviderService $serviceProviderService)
    {
        $this->serviceProviderService = $serviceProviderService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = ServiceProvider::all();
            return $this->serviceProviderService->datatable();
        }
        return view('admin.service-provider.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $radioStations= RadioStation::select('id', 'name')->get();
        $services = Service::select('id', 'name')->get();
        return view('admin.service-provider.create', compact('radioStations', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->serviceProviderService->validator($request->all());
        $validatedData['service_category_id'] = Service::where('id', $validatedData['service_id'])->first()->service_category_id;
        try {
            $this->serviceProviderService->store($validatedData);
            return redirect()->route('serviceProvider.index')->with([
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
        $data = ServiceProvider::findOrFail($id);
        $radioStations= RadioStation::select('id', 'name')->get();
        $services = Service::select('id', 'name')->get();
        return view('admin.service-provider.edit', compact('data', 'radioStations', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $this->serviceProviderService->validator($request->all(), $id);
            $data = $this->serviceProviderService->update($id, $validatedData);
            return redirect()->route('serviceProvider.index')->with([
                'message' => 'Data stored successfully.',
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
    public function destroy(string $id)
    {
        try {
            $data = $this->serviceProviderService->delete($id);
            return redirect()->route('serviceProvider.index')->with([
                'message' => 'Data stored successfully.',
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
