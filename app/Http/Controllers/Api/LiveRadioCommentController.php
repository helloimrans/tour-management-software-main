<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LiveRadioCommentService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LiveRadioCommentController extends Controller

{
    use ApiResponse;

    protected $liveRadioCommentService;

    public function __construct(LiveRadioCommentService $liveRadioCommentService)
    {
        $this->liveRadioCommentService = $liveRadioCommentService;
    }

    public function index()
    {
        $radioStationId = request('radio_station_id');
        try {
            $data = $this->liveRadioCommentService->getAll($radioStationId) ?? [];
            return $this->ResponseSuccess($data, 'Data fetch successfully!');
        } catch (\Throwable $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $this->liveRadioCommentService->validator($request->all());
            $data = $this->liveRadioCommentService->store($validatedData);
            return $this->ResponseSuccess($data, 'Data inserted successfully!');
        } catch (ValidationException $exception) {
            return $this->ResponseError($exception->validator->errors());
        } catch (\Exception $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }
}
