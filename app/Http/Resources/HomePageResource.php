<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomePageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'sliders' => SliderResource::collection($this->sliders),
            'radio_station_id' => $this->radio_station->id,
            'is_relaks_tv' => $this->radio_station->is_relaks_tv,
            'live_radio_url' => $this->radio_station->live_radio_url,
            'thumbnail_image' => $this->radio_station->thumbnail_image ? $this->radio_station->thumbnail_url : null,
            'ad_sliders' => AdSliderResource::collection($this->ad_sliders),
            'is_under_maintenence' => $this->radio_station->is_under_maintenence,
            'under_maintenence_image' => $this->radio_station->under_maintenence_image ? $this->radio_station->under_maintenence_image_url : null,
            'live_youtube_url' => $this->radio_station->live_youtube_url,
            'category_with_music' => CategoryWithMusicResource::collection($this->category_with_music),
            'music_categories' => $this->formatMusicCategory($this->music_categories),
        ];
    }

    private function formatMusicCategory($categories)
    {
        return $categories->map(function($category) {
            return [
                'id' => $category->id,
                'radio_station_id' => $category->radio_station_id,
                'name' => $category->name,
            ];
        })->toArray();
    }
}
