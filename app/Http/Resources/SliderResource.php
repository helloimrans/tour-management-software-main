<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'radio_station_id' => $this->radio_station_id,
            'title' => $this->title,
            'description' => $this->description,
            'thumbnail_image' => $this->thumbnail_image ? $this->thumbnail_url : null,
            'weekday' => $this->weekday,
            'local_time' => $this->local_time,
            'usa_time' => $this->usa_time,
        ];
    }
}
