<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomePageResource;
use App\Http\Resources\HomePageTvResource;
use App\Models\RadioStation;
use App\Services\HomePageService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class HomePageController extends Controller

{
    use ApiResponse;

    protected $homePageService;

    public function __construct(HomePageService $homePageService)
    {
        $this->homePageService = $homePageService;
    }

    public function index()
    {
        $radioStationId = request("radio_station_id");
        try {
            $radioStation =  RadioStation::find($radioStationId);
            if ($radioStation->is_relaks_tv == 1) {
                $data = new HomePageTvResource($this->homePageService->getAllTv($radioStationId));
                return $this->ResponseSuccess($data, 'Data fetch successfully!');
            } else {
                $data = new HomePageResource((object) $this->homePageService->getAll($radioStationId));
                return $this->ResponseSuccess($data, 'Data fetch successfully!');
            }
        } catch (\Throwable $e) {
            return  $this->ResponseError($e->getMessage());
        }
    }
}
