<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RelaksTvResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'radio_station_id' => $this->radio_station_id,
            'youtube_url_1' => $this->youtube_url_1,
            'youtube_url_2' => $this->youtube_url_2,
            'youtube_url_3' => $this->youtube_url_3,
        ];
    }
}
