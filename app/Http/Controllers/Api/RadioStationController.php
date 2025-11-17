<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RadioStationResource;
use Illuminate\Http\Request;
use App\Services\RadioStationService;
use App\Traits\ApiResponse;
use Illuminate\Validation\ValidationException;

class RadioStationController extends Controller
{
    use ApiResponse;

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
        try {
            $data = RadioStationResource::collection($this->radioStationService->getAll());
            return $this->ResponseSuccess($data, 'Data fetch successfully!');
        } catch (\Throwable $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->radioStationService->validator($request->all());
            $data = $this->radioStationService->store($validatedData);
            return $this->ResponseSuccess($data, 'Data inserted successfully!');
        }catch (ValidationException $exception) {
                return $this->ResponseError($exception->validator->errors());
         } catch (\Exception $e) {
            return  $this->ResponseError($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = $this->radioStationService->show($id);
            return $this->ResponseSuccess($data, 'Data fetch successfully!');
        } catch (\Throwable $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $this->radioStationService->validator($request->all());
            $data = $this->radioStationService->update($id, $validatedData);
            return $this->ResponseSuccess($data, 'Data updated successfully!');
        }catch (ValidationException $exception) {
                return $this->ResponseError($exception->validator->errors());
         } catch (\Exception $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = $this->radioStationService->delete($id);
            return $this->ResponseSuccess($data, 'Data deleted successfully!');
        } catch (\Throwable $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }
}
