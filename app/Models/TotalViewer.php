<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TotalViewer extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
   

    public function radioStation()
    {
        return $this->belongsTo(RadioStation::class, 'radio_station_id');
    }
}
