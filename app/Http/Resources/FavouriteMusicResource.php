<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavouriteMusicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'radio_station_id' => $this->radio_station_id,
            'user_id' => $this->user_id,
            'music_id' => $this->music_id,
            'muisc' => new MusicResource($this->music),
        ];
    }
}
