<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveRadioComment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function radioStation()
    {
        return $this->belongsTo(RadioStation::class, 'radio_station_id', 'id')->withDefault([
            'name' => '--',
        ]);
    }

    public function scopeIsActive($query){
        return $query->where(['is_active' => 1]);
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