<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class SettingsService
{
    public function validator(array $data, $id = null)
    {
        $rules = [
            'app_name' => ['required', 'string'],
            'app_slogan' => ['nullable', 'string'],
            'app_logo' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
            'app_background_image' => ['nullable', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
            'point_per_coupon' => ['nullable', 'integer'],
            'is_point_by_registration' => ['required', 'boolean'],
            'point_per_registration' => ['nullable', 'integer'],
        ];

        return Validator::make($data, $rules)->validate();
    }

    public function getSettings()
    {
        return Setting::latest()->first();
    }

    public function update($input)
    {
        $setting = $this->getSettings();

        if (isset($input['app_logo'])) {
            if (@$setting->app_logo) {
                deleteFile($setting->app_logo);
            }
            $input['app_logo'] = uploadFile($input['app_logo'], 'app_logo');
        }
        if (isset($input['app_background_image'])) {
            if (@$setting->app_background_image) {
                deleteFile($setting->app_background_image);
            }
            $input['app_background_image'] = uploadFile($input['app_background_image'], 'app_background_image');
        }

        if($setting){
            return $setting->update($input);
        }else{
            return Setting::create($input);
        }
    }
}
