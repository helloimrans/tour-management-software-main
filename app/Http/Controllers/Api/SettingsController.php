<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Services\SettingsService;
use App\Traits\ApiResponse;


class SettingsController extends Controller
{
    use ApiResponse;

    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        try {
            $data = new SettingResource($this->settingsService->getSettings());
            return $this->ResponseSuccess($data, 'Data fetched successfully!');

        } catch (\Throwable $e) {
            return $this->ResponseError($e->getMessage());
        }
    }




}
