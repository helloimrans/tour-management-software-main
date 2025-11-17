<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }

    public function serviceProviders()
    {
        return $this->hasMany(ServiceProvider::class);
    }
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
    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id', 'id')->withDefault([
            'name' => '--',
        ]);
    }
    public function radioStation()
    {
        return $this->belongsTo(RadioStation::class, 'radio_station_id', 'id')->withDefault([
            'name' => '--',
        ]);
    }
}
