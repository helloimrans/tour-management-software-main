<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleMusicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->music->id,
            'radio_station_id' => $this->music->radio_station_id,
            'music_category_id' => $this->music->music_category_id,
            'title' => $this->music->title,
            'description' => $this->music->description,
            'music_file' => $this->music->music_file ? $this->music->music_file_url : null,
            'music_author' => $this->music->music_author,
            'music_views' => $this->music->music_views,
            'thumbnail_image' => $this->music->thumbnail_image ? $this->music->thumbnail_url : null,
            'discussion_comments' => $this->music->discussion_comments,
            'category_with_music' => CategoryWithMusicResource::collection($this->category_with_music),
        ];
    }
}
