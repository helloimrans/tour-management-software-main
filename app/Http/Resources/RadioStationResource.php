<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RadioStationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'logo' => $this->logo ? $this->logo_url : null,
            'is_under_maintenance' => $this->is_under_maintenance,
            'under_maintenence_image' => $this->under_maintenence_image ? $this->under_maintenence_image_url : null,
            'live_radio_url' => $this->live_radio_url,
            'thumbnail_image' => $this->thumbnail ? $this->thumbnail_url : null,
            'phone_number' => $this->phone_number,
            'whatsapp_number' => $this->whatsapp_number,
        ];
    }
}
