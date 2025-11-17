<?php

namespace App\Services;


use Illuminate\Support\Facades\Validator;

class HomeSliderService3
{
    public function getAll()
    {
        return HomeSlider::all();
    }

    public function show(string $id)
    {
        return HomeSlider::findOrFail($id);
    }

    public function store(array $data)
    {
        return HomeSlider::create($data);
    }

    public function update(string $id, array $data)
    {
        $slider = HomeSlider::findOrFail($id);
        $slider->update($data);
        return $slider;
    }

    public function delete(string $id)
    {
        $slider = HomeSlider::findOrFail($id);
        $slider->delete();
        return $slider;
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'radio_station_id' => 'nullable|exists:radio_stations,id',
            'title' => 'required|string|max:255|unique:sliders,title',
            'description' => 'nullable|string',
            'thumbnail_image' => 'nullable|string',
            'weekday' => 'nullable|boolean',
            'local_time' => 'nullable|boolean',
            'usa_time' => 'nullable|boolean',
            'created_by' => 'nullable|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
            'deleted_by' => 'nullable|exists:users,id',
        ])->validate();
    }
}
