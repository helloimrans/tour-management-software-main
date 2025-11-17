<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\TotalViewer;
use App\Services\ViewersService;
use App\Traits\ApiResponse;


class ViewersController extends Controller
{
    use ApiResponse;

    protected ViewersService $viewersService;

    public function __construct(ViewersService $viewersService)
    {
        $this->viewersService = $viewersService;
    }

    public function index()
    {
        try {
            $viewers = $this->viewersService->getViewers();
            $data['viewers'] = $viewers;
            return $this->ResponseSuccess($data, 'Data fetched successfully!');

        } catch (\Throwable $e) {
            return $this->ResponseError($e->getMessage());
        }
    }




}
