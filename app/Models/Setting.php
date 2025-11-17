<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['app_logo_url', 'app_background_image_url'];

    public function getAppLogoUrlAttribute(): string
    {
        return url(Storage::url($this->app_logo));
    }
    
    public function getAppBackgroundImageUrlAttribute(): string
    {
        return url(Storage::url($this->app_background_image));
    }
}
