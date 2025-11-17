<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusicCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];


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

    public function music()
    {
        return $this->hasMany(Music::class, 'music_category_id', 'id');
    }

    public function musics()
    {
        return $this->belongsToMany(Music::class, 'music_category_music');
    }

    public function radioStation()
    {
        return $this->belongsTo(RadioStation::class, 'radio_station_id', 'id')->withDefault([
            'name' => '--',
        ]);
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
