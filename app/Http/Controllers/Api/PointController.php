<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Services\PointService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PointController extends Controller
{
    use ApiResponse;

    protected PointService $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    public function details()
    {
        try {
            $data = new SettingResource($this->pointService->getPointDetails());
            return $this->ResponseSuccess($data, 'Data fetched successfully!');
        } catch (\Throwable $e) {
            return $this->ResponseError($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $this->pointService->validator($request->all());
            $data = $this->pointService->store($validatedData);
            return $this->ResponseSuccess($data, 'Data stored successfully!');
        } catch (\Throwable $e) {
            return $this->ResponseError($e->getMessage());
        }
    }
}
