<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MusicResource extends JsonResource
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
            'radio_station_id' => $this->radio_station_id,
            'title' => $this->title,
            'description' => $this->description,
            'music_file' => $this->music_file ? $this->music_file_url : null,
            'music_author' => $this->music_author,
            'music_views' => $this->music_views,
            'thumbnail_image' => $this->thumbnail_image ? $this->thumbnail_url : null,
            'discussion_comments' => $this->discussion_comments,
            'music_categories' => $this->musicCategories,
        ];
    }
}
