<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'app_name' => $this->app_name,
            'app_slogan' => $this->app_slogan,
            'app_logo' => $this->app_logo ? $this->app_logo_url : null,
            'app_background_image' => $this->app_background_image ? $this->app_background_image_url : null,
        ];
    }
}
