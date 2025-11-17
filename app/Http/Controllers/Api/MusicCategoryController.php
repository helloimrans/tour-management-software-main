<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MusicCategoryResource;
use App\Services\MusicCategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MusicCategoryController extends Controller

{
    use ApiResponse;

    protected $musicCategoryService;

    public function __construct(MusicCategoryService $musicCategoryService)
    {
        $this->musicCategoryService = $musicCategoryService;
    }

    public function index()
    {
        $radioStationId = request('radio_station_id');
        try {
            $data = MusicCategoryResource::collection($this->musicCategoryService->getAll($radioStationId));
            return $this->ResponseSuccess($data, 'Data fetch successfully!');
        } catch (\Throwable $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $data = new MusicCategoryResource($this->musicCategoryService->show($id));
            return $this->ResponseSuccess($data, 'Data fetch successfully!');
        } catch (\Throwable $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }
}
