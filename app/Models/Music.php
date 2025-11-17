<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;


class Music extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $appends = ['thumbnail_url', 'music_file_url'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault([
            'name' => '--',
        ]);
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->withDefault([
            'name' => '--',
        ]);
    }
    public function musicCategory()
    {
        return $this->belongsTo(MusicCategory::class, 'music_category_id', 'id');
    }

    public function musicCategories()
    {
        return $this->belongsToMany(MusicCategory::class, 'music_category_music');
    }

    public function radioStation()
    {
        return $this->belongsTo(RadioStation::class, 'radio_station_id', 'id');
    }

    public function getThumbnailUrlAttribute(): string
    {
        return url(Storage::url($this->thumbnail_image));
    }
    public function getMusicFileUrlAttribute(): string
    {
        return url(Storage::url($this->music_file));
    }

    public function scopeForRadioStation($query)
    {
        if (auth()->user()->radio_station_id != null) {
            return $query->where(['radio_station_id' => auth()->user()->radio_station_id]);
        } else {
            return $query;
        }
    }
}
