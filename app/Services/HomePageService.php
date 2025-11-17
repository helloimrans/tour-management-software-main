<?php


namespace App\Services;

use App\Models\AdSlider;
use App\Models\MusicCategory;
use App\Models\RadioStation;
use App\Models\Slider;

class HomePageService
{

    public function getAll($radioStationId = null)
    {
        $data['sliders'] = Slider::where('radio_station_id', $radioStationId)->get();
        $data['ad_sliders'] = AdSlider::where('radio_station_id', $radioStationId)->get();

        $data['music_categories'] = MusicCategory::where('radio_station_id', $radioStationId)
            ->with(['musics'])
            ->latest()
            ->get();

        $data['category_with_music'] = $data['music_categories']->take(2);
        
        $data['radio_station'] = RadioStation::select([
            'id', 'live_radio_url', 'thumbnail_image',
            'is_under_maintenence', 'under_maintenence_image',
            'live_youtube_url', 'is_relaks_tv'
        ])->where('id', $radioStationId)->first();

        return $data;
    }


    public function getAllTv($radioStationId = null)
    {
        return RadioStation::with('relaksTv')->where(['id' => $radioStationId])->first();
    }
}
