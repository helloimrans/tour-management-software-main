<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomePageTvResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'radio_station_id' => $this->id,
            'is_relaks_tv' => $this->is_relaks_tv,
            'radio_station_name' => $this->name,
            'relaks_tv' => new RelaksTvResource($this->relaksTv),
        ];
    }
}
