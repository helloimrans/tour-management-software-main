<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCategoryResource;
use App\Http\Resources\ServiceWithProviderResource;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Traits\ApiResponse;


class ServiceController extends Controller
{
    use ApiResponse;

    public function getServiceCategories()
    {
        try {
            $services = ServiceCategoryResource::collection(ServiceCategory::with(['services'])->isActive()->get());
            return $this->ResponseSuccess($services, 'Data fetched successfully!');
        } catch (\Throwable $e) {
            return $this->ResponseError($e->getMessage());
        }
    }

    public function getServiceProviders($serviceId)
    {
        try {
            $serviceCategory = new ServiceWithProviderResource(Service::with(['serviceProviders'])->find($serviceId));
            return $this->ResponseSuccess($serviceCategory, 'Data fetched successfully!');
        } catch (\Throwable $e) {
            return $this->ResponseError($e->getMessage());
        }
    }
}
